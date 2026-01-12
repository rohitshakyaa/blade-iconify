# Generating icons (SVG extraction)

This package includes an Artisan command:

```bash
php artisan iconify:extract-svgs
```

It will:

1. Read `config('blade-iconify.icons')`
2. For each `<set>:<icon>`:
   - locate the Iconify JSON for `<set>` via `Iconify\IconsJSON\Finder::locate($set)`
   - read `icons[$icon].body`, plus width/height
   - wrap it into a valid `<svg ...>` element
3. Write the file as:

- `{output}/...` (see **Output location** below)

## Output location

You can control where SVGs are written/read from in **three ways**.

### 1) Config (recommended)

In `config/blade-iconify.php`:

```php
'export_to' => 'package', // package|project|custom
'custom_path' => null,
```

- `package` (default): `{package}/resources/svg`
- `project`: `{app}/resources/svg`
- `custom`: `base_path(custom_path)`

### 2) CLI flag (one-off)

```bash
php artisan iconify:extract-svgs --project
```

This forces output to your app’s `resources/svg`.

### 3) Custom path (one-off)

```bash
php artisan iconify:extract-svgs --path=resources/icons
```

`--path` is relative to your project root (`base_path()`).

## Options

### `--overwrite`

If an SVG already exists, it will be skipped by default.

```bash
php artisan iconify:extract-svgs --overwrite
```

### `--optimize`

A tiny “safe” cleanup:

- trims trailing whitespace
- normalizes newlines

```bash
php artisan iconify:extract-svgs --optimize
```

## Where does it get the Iconify JSON from?

The command uses:

```php
Finder::locate($setName)
```

So your app must have Iconify JSON sets available in a place that `Finder` can locate.

If you get errors like “Unable to locate set …”, see the troubleshooting doc.

## Cache icons (recommended)

Blade Icons caches discovered icons for performance.

After generating new SVGs (especially in production), run:

```bash
php artisan icons:cache
```

If you add/remove SVGs during development and icons don’t update, clear the cache:

```bash
php artisan icons:clear
```
