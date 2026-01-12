# Blade Iconify (rohitshakya/blade-iconify)

A small Laravel package that lets you use **Iconify** icons as **Blade Icons** components (Blade UI Kit) — so you can also use them inside UI kits like **TallStackUI**.

- ✅ Configure a *whitelist* of Iconify icon IDs in `config/blade-iconify.php`
- ✅ Generate SVGs via an Artisan command
- ✅ Register those SVGs as a Blade Icons set with your chosen prefix (default: `rsi`)
- ✅ Choose where SVGs live: **package** or **project** `resources/svg`

---

## Requirements

- PHP + Laravel (compatible with `blade-ui-kit/blade-icons`)
- `blade-ui-kit/blade-icons` (installed automatically)
- `iconify/json` (installed automatically)

> The extraction command uses `Iconify\IconsJSON\Finder::locate($setName)` to locate Iconify JSON sets.

---

## Install

```bash
composer require rohitshakya/blade-iconify
php artisan optimize:clear
```

Publish the config (recommended):

```bash
php artisan vendor:publish --tag=blade-iconify-config
```

---

## Configure

Edit `config/blade-iconify.php`:

```php
return [
    // Blade component prefix: <x-rsi-... />
    'set_prefix' => 'rsi',

    // Where SVGs are written/read from:
    //  - package (default): {vendor}/rohitshakya/blade-iconify/resources/svg
    //  - project          : {app}/resources/svg
    //  - custom           : base_path(custom_path)
    'export_to' => 'package',
    'custom_path' => null,

    // Only icons listed here will be generated and available
    'icons' => [
        'material-symbols-light:10k-sharp',
        // 'lucide:activity',
        // 'mdi:home',
    ],
];
```

Icon ID format:

- `<set>:<icon-name>` (example: `lucide:activity`)

---

## Generate SVGs

```bash
php artisan iconify:extract-svgs
```

### Output location (3 ways)

**1) Config (permanent):**

```php
// config/blade-iconify.php
'export_to' => 'project',
```

**2) CLI flag (one-off):**

```bash
php artisan iconify:extract-svgs --project
```

**3) Custom path (one-off):**

```bash
php artisan iconify:extract-svgs --path=resources/svg
# or any folder
php artisan iconify:extract-svgs --path=resources/icons
```

### Other options

```bash
php artisan iconify:extract-svgs --overwrite --optimize
```

File naming:

- `{set}-{icon}.svg`  
  Example: `lucide-activity.svg`

---

## Cache / clear icons (Blade Icons)

After generating icons (especially in production), run:

```bash
php artisan icons:cache
```

If icons aren’t showing while developing:

```bash
php artisan icons:clear
```

---

## Use in Blade

With default prefix (`rsi`):

```blade
<x-rsi-material-symbols-light-10k-sharp class="w-6 h-6" />
```

---

## Use in TallStackUI

TallStackUI uses Blade Icons internally. Once the icon exists as a Blade component, you can reference it by name.

Example:

```blade
<x-ts-icon name="rsi-material-symbols-light-10k-sharp" />
```

---

## Keeping `resources/svg/` but ignoring generated SVGs

If you want to keep the folder in Git but ignore all generated SVG files:

```gitignore
# Ignore generated SVGs
resources/svg/*.svg

# Keep the folder tracked
!resources/svg/.gitkeep
```

Then create the placeholder:

```bash
touch resources/svg/.gitkeep
```

---

## Documentation

- [Configuration](docs/configuration.md)
- [Generating icons](docs/generating-icons.md)
- [Usage (Blade + TallStackUI)](docs/usage.md)
- [Troubleshooting](docs/troubleshooting.md)
- [Contributing / local development](docs/development.md)

---

## License

MIT. See `LICENSE`.
