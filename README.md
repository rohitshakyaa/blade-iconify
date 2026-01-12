# Blade Iconify (rohitshakya/blade-iconify)

A small Laravel package that lets you use **Iconify** icons as **Blade Icons** components (Blade UI Kit) — so you can also use them inside UI kits like **TallStackUI**.

- ✅ Configure a *whitelist* of Iconify icon IDs in `config/blade-iconify.php`
- ✅ Generate SVGs into `resources/svg/` using an Artisan command
- ✅ Register those SVGs as a Blade Icons set with your chosen prefix (default: `rsi`)

---

## Requirements

- PHP + Laravel (any version compatible with `blade-ui-kit/blade-icons`)
- `blade-ui-kit/blade-icons` (installed automatically)
- An **Iconify JSON icon set source** resolvable by `Iconify\IconsJSON\Finder`
  - If you don’t already have this in your app, install the Iconify JSON locator package used by the command (see docs).

> Note: the extraction command uses `Iconify\IconsJSON\Finder::locate($setName)` to find the JSON for an icon set.
> Your project must have Iconify JSON sets available in a place `Finder` can locate.

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

## Configure icons (whitelist)

Edit `config/blade-iconify.php`:

```php
return [
    // Blade component prefix: <x-rsi-... />
    'set_prefix' => 'rsi',

    // Only icons listed here will be generated and available
    'icons' => [
        'material-symbols-light:10k-sharp',
        // 'lucide:activity',
        // 'mdi:home',
    ],
];
```

Icon ID format:

- `<set>:<icon-name>`
- Example: `lucide:activity`

---

## Generate SVGs

```bash
php artisan iconify:extract-svgs
```

Options:

```bash
php artisan iconify:extract-svgs --overwrite --optimize
```

Outputs SVG files into:

- `vendor/rohitshakya/blade-iconify/resources/svg/` (inside the package)
- When developing the package locally, it writes to `resources/svg/` in the package directory.

File naming:

- `{set}-{icon}.svg`  
  Example: `lucide-activity.svg`

---

## Use in Blade

With the default prefix (`rsi`), this:

- `material-symbols-light:10k-sharp`

becomes:

```blade
<x-rsi-material-symbols-light-10k-sharp class="w-6 h-6" />
```

> Blade Icons maps `:` to `-` via the file naming convention we use (`{set}-{icon}.svg`).

---

## Use in TallStackUI

TallStackUI uses Blade Icons internally. Once the icon exists as a Blade component, you can reference it by name.

Example (varies by TallStackUI version/config):

```blade
<x-ts-icon name="rsi-material-symbols-light-10k-sharp" />
```

If your TallStackUI expects a different attribute, keep the **icon name** the same as the Blade component name.

---

## Publishing SVGs (optional)

You usually **do not** need to publish SVGs publicly because Blade Icons reads from the filesystem.

If you want them available under `public/` (for debugging or CDN usage):

```bash
php artisan vendor:publish --tag=blade-iconify-assets
```

This publishes to:

- `public/vendor/rohitshakya-iconify`

---

## Keeping `resources/svg/` but ignoring generated SVGs

If you want to keep the folder in Git but ignore all SVG files inside it:

```gitignore
# Ignore generated SVGs
resources/svg/*.svg

# Keep the folder tracked
!resources/svg/.gitkeep
```

Then create an empty file:

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

MIT. See `LICENSE`.# Blade Iconify (rohitshakya/blade-iconify)

A small Laravel package that lets you use **Iconify** icons as **Blade Icons** components (Blade UI Kit) — so you can also use them inside UI kits like **TallStackUI**.

- ✅ Configure a *whitelist* of Iconify icon IDs in `config/blade-iconify.php`
- ✅ Generate SVGs into `resources/svg/` using an Artisan command
- ✅ Register those SVGs as a Blade Icons set with your chosen prefix (default: `rsi`)

---

## Requirements

- PHP + Laravel (any version compatible with `blade-ui-kit/blade-icons`)
- `blade-ui-kit/blade-icons` (installed automatically)
- An **Iconify JSON icon set source** resolvable by `Iconify\IconsJSON\Finder`
  - If you don’t already have this in your app, install the Iconify JSON locator package used by the command (see docs).

> Note: the extraction command uses `Iconify\IconsJSON\Finder::locate($setName)` to find the JSON for an icon set.
> Your project must have Iconify JSON sets available in a place `Finder` can locate.

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

## Configure icons (whitelist)

Edit `config/blade-iconify.php`:

```php
return [
    // Blade component prefix: <x-rsi-... />
    'set_prefix' => 'rsi',

    // Only icons listed here will be generated and available
    'icons' => [
        'material-symbols-light:10k-sharp',
        // 'lucide:activity',
        // 'mdi:home',
    ],
];
```

Icon ID format:

- `<set>:<icon-name>`
- Example: `lucide:activity`

---

## Generate SVGs

```bash
php artisan iconify:extract-svgs
```

Options:

```bash
php artisan iconify:extract-svgs --overwrite --optimize
```

Outputs SVG files into:

- `vendor/rohitshakya/blade-iconify/resources/svg/` (inside the package)
- When developing the package locally, it writes to `resources/svg/` in the package directory.

File naming:

- `{set}-{icon}.svg`  
  Example: `lucide-activity.svg`

---

## Use in Blade

With the default prefix (`rsi`), this:

- `material-symbols-light:10k-sharp`

becomes:

```blade
<x-rsi-material-symbols-light-10k-sharp class="w-6 h-6" />
```

> Blade Icons maps `:` to `-` via the file naming convention we use (`{set}-{icon}.svg`).

---

## Use in TallStackUI

TallStackUI uses Blade Icons internally. Once the icon exists as a Blade component, you can reference it by name.

Example (varies by TallStackUI version/config):

```blade
<x-ts-icon name="rsi-material-symbols-light-10k-sharp" />
```

If your TallStackUI expects a different attribute, keep the **icon name** the same as the Blade component name.

---

## Publishing SVGs (optional)

You usually **do not** need to publish SVGs publicly because Blade Icons reads from the filesystem.

If you want them available under `public/` (for debugging or CDN usage):

```bash
php artisan vendor:publish --tag=blade-iconify-assets
```

This publishes to:

- `public/vendor/rohitshakya-iconify`

---

## Keeping `resources/svg/` but ignoring generated SVGs

If you want to keep the folder in Git but ignore all SVG files inside it:

```gitignore
# Ignore generated SVGs
resources/svg/*.svg

# Keep the folder tracked
!resources/svg/.gitkeep
```

Then create an empty file:

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

MIT. See `LICENSE`.# Blade Iconify (rohitshakya/blade-iconify)

A small Laravel package that lets you use **Iconify** icons as **Blade Icons** components (Blade UI Kit) — so you can also use them inside UI kits like **TallStackUI**.

- ✅ Configure a *whitelist* of Iconify icon IDs in `config/blade-iconify.php`
- ✅ Generate SVGs into `resources/svg/` using an Artisan command
- ✅ Register those SVGs as a Blade Icons set with your chosen prefix (default: `rsi`)

---

## Requirements

- PHP + Laravel (any version compatible with `blade-ui-kit/blade-icons`)
- `blade-ui-kit/blade-icons` (installed automatically)
- An **Iconify JSON icon set source** resolvable by `Iconify\IconsJSON\Finder`
  - If you don’t already have this in your app, install the Iconify JSON locator package used by the command (see docs).

> Note: the extraction command uses `Iconify\IconsJSON\Finder::locate($setName)` to find the JSON for an icon set.
> Your project must have Iconify JSON sets available in a place `Finder` can locate.

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

## Configure icons (whitelist)

Edit `config/blade-iconify.php`:

```php
return [
    // Blade component prefix: <x-rsi-... />
    'set_prefix' => 'rsi',

    // Only icons listed here will be generated and available
    'icons' => [
        'material-symbols-light:10k-sharp',
        // 'lucide:activity',
        // 'mdi:home',
    ],
];
```

Icon ID format:

- `<set>:<icon-name>`
- Example: `lucide:activity`

---

## Generate SVGs

```bash
php artisan iconify:extract-svgs
```

Options:

```bash
php artisan iconify:extract-svgs --overwrite --optimize
```

Outputs SVG files into:

- `vendor/rohitshakya/blade-iconify/resources/svg/` (inside the package)
- When developing the package locally, it writes to `resources/svg/` in the package directory.

File naming:

- `{set}-{icon}.svg`  
  Example: `lucide-activity.svg`

---

## Use in Blade

With the default prefix (`rsi`), this:

- `material-symbols-light:10k-sharp`

becomes:

```blade
<x-rsi-material-symbols-light-10k-sharp class="w-6 h-6" />
```

> Blade Icons maps `:` to `-` via the file naming convention we use (`{set}-{icon}.svg`).

---

## Use in TallStackUI

TallStackUI uses Blade Icons internally. Once the icon exists as a Blade component, you can reference it by name.

Example (varies by TallStackUI version/config):

```blade
<x-ts-icon name="rsi-material-symbols-light-10k-sharp" />
```

If your TallStackUI expects a different attribute, keep the **icon name** the same as the Blade component name.

---

## Publishing SVGs (optional)

You usually **do not** need to publish SVGs publicly because Blade Icons reads from the filesystem.

If you want them available under `public/` (for debugging or CDN usage):

```bash
php artisan vendor:publish --tag=blade-iconify-assets
```

This publishes to:

- `public/vendor/rohitshakya-iconify`

---

## Keeping `resources/svg/` but ignoring generated SVGs

If you want to keep the folder in Git but ignore all SVG files inside it:

```gitignore
# Ignore generated SVGs
resources/svg/*.svg

# Keep the folder tracked
!resources/svg/.gitkeep
```

Then create an empty file:

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