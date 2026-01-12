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

- `resources/svg/{set}-{icon}.svg`

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

So your app must have an Iconify JSON source that `Finder` can locate.

Common approaches:

- Install a PHP package that provides Iconify JSON sets + a `Finder` locator
- Vendor the needed JSON sets in your repo and adjust `Finder` to point there (advanced)

If you get errors like “Unable to locate set …”, see the troubleshooting doc.


### Cache Icons (Recommended in Production)

```bash
php artisan icons:cache
```

This command scans all registered icon sets and caches them for faster performance in production.

👉 You should run this after:

* Installing the package
* Generating new SVG icons
* Deploying to production

### Clear Icon Cache

```bash
php artisan icons:clear
```

This clears the Blade Icons cache.

👉 Use this when:

* You add/remove SVG files
* Icons are not showing after regeneration
* During development