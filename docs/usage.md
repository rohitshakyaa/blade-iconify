# Usage

## Blade

Once SVGs exist and the package is installed, icons become Blade components.

Example icon ID:

- `material-symbols-light:10k-sharp`

Generated SVG:

- `resources/svg/material-symbols-light-10k-sharp.svg` (when `export_to=project`)
- `{package}/resources/svg/material-symbols-light-10k-sharp.svg` (when `export_to=package`)

Blade component:

```blade
<x-rsi-material-symbols-light-10k-sharp class="w-6 h-6" />
```

> The prefix `rsi` comes from `config('blade-iconify.set_prefix')`.

## TallStackUI

TallStackUI integrates with Blade Icons. After the Blade component exists, you can reference it by its component name.

Example:

```blade
<x-ts-icon name="rsi-material-symbols-light-10k-sharp" />
```

If your TallStackUI version uses a different prop (for example `icon` instead of `name`), use the same icon string.


## Icon Cache Commands

This package relies on Blade Icons internal caching.

If new icons are not appearing:

```bash
php artisan icons:clear
```

For production optimization:

```bash
php artisan icons:cache
```

Always clear cache after running:

```bash
php artisan iconify:extract-svgs
```
