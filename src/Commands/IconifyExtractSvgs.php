<?php

namespace RohitShakya\BladeIconify\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Iconify\IconsJSON\Finder;
use Illuminate\Support\Facades\Artisan;
use Throwable;

class IconifyExtractSvgs extends Command
{
    /**
     * Example:
     *  php artisan iconify:extract-svgs
     *  php artisan iconify:extract-svgs --overwrite --optimize
     *  php artisan iconify:extract-svgs --project
     *  php artisan iconify:extract-svgs --path=resources/svg
     */
    protected $signature = 'iconify:extract-svgs
        {--project : Export SVGs to the host project (resources/svg)}
        {--path= : Custom output path (relative to project root, e.g. resources/svg)}
        {--overwrite : Overwrite existing svg files}
        {--optimize : Basic cleanup (trim + normalize newlines)}
    ';

    protected $description = 'Extract whitelisted Iconify icons (from config) into SVG files for Blade Icons.';

    public function handle(): int
    {
        $icons = (array) config('blade-iconify.icons', []);
        $setPrefix = (string) (config('blade-iconify.set_prefix') ?? 'rsi');

        if (empty($icons)) {
            $this->warn('No icons configured. Add icon IDs in config("blade-iconify.icons").');
            return self::SUCCESS;
        }

        $packageRoot = $this->packageRoot();
        [$outDir, $outMode] = $this->resolveOutputDirectory($packageRoot);

        File::ensureDirectoryExists($outDir);

        $overwrite = (bool) $this->option('overwrite');
        $optimize = (bool) $this->option('optimize');

        $this->newLine();
        $this->line('<info>Blade Iconify</info> — SVG extraction');
        $this->line('Output: <comment>' . $this->relativeToCwd($outDir) . "</comment>");
        $this->line('Output mode: <comment>' . $outMode . '</comment>');
        $this->line('Set prefix: <comment>' . $setPrefix . '</comment>');
        $this->line('Icons: <comment>' . count($icons) . "</comment>");
        $this->newLine();

        // Cache: setName => decoded JSON
        $setCache = [];
        $ok = 0;
        $skipped = 0;
        $failed = 0;

        $bar = $this->output->createProgressBar(count($icons));
        $bar->start();

        foreach ($icons as $iconId) {
            $bar->advance();

            $iconId = trim((string) $iconId);
            if ($iconId === '' || !str_contains($iconId, ':')) {
                $failed++;
                $this->newLine();
                $this->error("Invalid icon id: '{$iconId}'. Expected '<set>:<name>'.");
                continue;
            }

            [$setName, $iconName] = explode(':', $iconId, 2);
            $setName = trim($setName);
            $iconName = trim($iconName);

            if ($setName === '' || $iconName === '') {
                $failed++;
                $this->newLine();
                $this->error("Invalid icon id: '{$iconId}'. Expected '<set>:<name>'.");
                continue;
            }

            try {
                $setJson = $setCache[$setName] ??= $this->loadSetJson($setName);

                if (!isset($setJson['icons'][$iconName])) {
                    $failed++;
                    $this->newLine();
                    $this->error("Icon not found in set '{$setName}': '{$iconName}'");
                    continue;
                }

                $icon = $setJson['icons'][$iconName];

                // width/height can be per-icon or per-set, and height may be missing
                $width  = (int) ($icon['width']  ?? $setJson['width']  ?? 24);
                $height = (int) ($icon['height'] ?? $setJson['height'] ?? 24);

                $svg = $this->wrapSvg((string) ($icon['body'] ?? ''), $width, $height);

                if ($optimize) {
                    $svg = $this->basicOptimize($svg);
                }

                $fileName = $setName . '-' . $iconName . '.svg';
                $filePath = $outDir . DIRECTORY_SEPARATOR . $fileName;

                if (File::exists($filePath) && !$overwrite) {
                    $skipped++;
                    continue;
                }

                File::put($filePath, $svg);
                $ok++;
            } catch (Throwable $e) {
                $failed++;
                $this->newLine();
                $this->error("Failed: {$iconId}");
                $this->line('<comment>' . $e->getMessage() . '</comment>');
                continue;
            }
        }

        $bar->finish();

        $this->newLine(2);
        $this->info("Done.");
        $this->table(
            ['Result', 'Count'],
            [
                ['Written', (string) $ok],
                ['Skipped', (string) $skipped],
                ['Failed', (string) $failed],
            ]
        );

        Artisan::call('icons:cache');

        if ($failed > 0) {
            $this->warn('Some icons failed. Fix the errors above and re-run the command.');
            return self::FAILURE;
        }

        return self::SUCCESS;
    }

    /**
     * Load and decode an Iconify set JSON using iconify/icons-json finder.
     */
    private function loadSetJson(string $setName): array
    {
        $setLocation = Finder::locate($setName);

        if (!$setLocation || !File::exists($setLocation)) {
            throw new \RuntimeException("Iconify set not found on disk for '{$setName}'. (Finder::locate returned empty)");
        }

        $raw = File::get($setLocation);

        $json = json_decode($raw, true);
        if (!is_array($json)) {
            throw new \RuntimeException("Unable to decode JSON for set '{$setName}'.");
        }

        if (!isset($json['icons']) || !is_array($json['icons'])) {
            throw new \RuntimeException("Invalid Iconify set JSON structure for '{$setName}' (missing 'icons').");
        }

        return $json;
    }

    private function wrapSvg(string $body, int $width, int $height): string
    {
        $body = trim($body);

        return <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 {$width} {$height}" width="{$width}" height="{$height}">
{$body}
</svg>

SVG;
    }

    /**
     * Very small "optimization" that is safe and dependency-free:
     * - trim
     * - normalize newlines
     * - remove trailing whitespace per line
     */
    private function basicOptimize(string $svg): string
    {
        $svg = str_replace(["\r\n", "\r"], "\n", $svg);
        $lines = array_map(static fn($l) => rtrim($l), explode("\n", $svg));
        $svg = implode("\n", $lines);

        return trim($svg) . "\n";
    }

    /**
     * Resolve package root assuming this file is: src/Commands/IconifyExtractSvgs.php
     */
    private function packageRoot(): string
    {
        return $this->normalizePath(dirname(__DIR__, 2));
    }

    /**
     * Decide where to write SVGs.
     *
     * Priority:
     *  1) --path
     *  2) --project
     *  3) config('blade-iconify.export_to') + config('blade-iconify.custom_path')
     *  4) package default: {package}/resources/svg
     *
     * @return array{0:string,1:string} [outputDirAbsolute, modeLabel]
     */
    private function resolveOutputDirectory(string $packageRoot): array
    {
        // 1) Explicit output path
        $custom = $this->option('path');
        if (is_string($custom) && trim($custom) !== '') {
            return [$this->normalizePath(base_path(trim($custom))), 'cli:--path'];
        }

        // 2) Force project resources/svg
        if ((bool) $this->option('project')) {
            return [$this->normalizePath(resource_path('svg')), 'cli:--project'];
        }

        // 3) Config-driven
        $mode = (string) (config('blade-iconify.export_to') ?? 'package');

        if ($mode === 'project') {
            return [$this->normalizePath(resource_path('svg')), 'config:project'];
        }

        if ($mode === 'custom') {
            $rel = (string) (config('blade-iconify.custom_path') ?? '');
            if (trim($rel) !== '') {
                return [$this->normalizePath(base_path(trim($rel))), 'config:custom'];
            }

            // Misconfigured, fall back safely
            return [$this->normalizePath($packageRoot . DIRECTORY_SEPARATOR . 'resources/svg'), 'config:custom (fallback:package)'];
        }

        // 4) Default: package
        return [$this->normalizePath($packageRoot . DIRECTORY_SEPARATOR . 'resources/svg'), 'config:package'];
    }

    private function normalizePath(string $path): string
    {
        return rtrim($path, DIRECTORY_SEPARATOR);
    }

    private function relativeToCwd(string $path): string
    {
        $cwd = $this->normalizePath(getcwd() ?: '');
        $path = $this->normalizePath($path);

        if ($cwd !== '' && str_starts_with($path, $cwd . DIRECTORY_SEPARATOR)) {
            return '.' . DIRECTORY_SEPARATOR . substr($path, strlen($cwd) + 1);
        }

        return $path;
    }
}
