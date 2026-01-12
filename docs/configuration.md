# Configuration

The package config lives at: `config/blade-iconify.php`.

## `set_prefix`

This is the Blade Icons prefix used for Blade components.

Example:

```php
'set_prefix' => 'rsi',
```

Now icons are used like:

```blade
<x-rsi-collection-icon-name />
```

## `export_to`

Controls **where SVGs are written/read from**.

Supported values:

- `package` (default): the package folder `resources/svg`
- `project`: your Laravel app folder `resources/svg`
- `custom`: a custom folder from your project root (using `custom_path`)

### Example: store icons in the project

```php
// config/blade-iconify.php
'export_to' => 'project',
```

This will:

- Generate SVGs into: `resources/svg` (your Laravel app)
- Register Blade Icons to read from: `resource_path('svg')`

### Example: store icons in a custom folder

```php
// config/blade-iconify.php
'export_to' => 'custom',
'custom_path' => 'resources/icons',
```

This will:

- Generate SVGs into: `base_path('resources/icons')`
- Register Blade Icons to read from that same path

## `custom_path`

Only used when `export_to = custom`.

- Must be a **relative** path from your project root (Laravel `base_path()`)
- Example: `resources/icons` or `storage/app/icons/svg`

## `icons` (whitelist)

Only icons listed here will be extracted and available.

```php
'icons' => [
    'lucide:activity',
    'mdi:home',
],
```

### Rules

- Each entry must be `'<set>:<icon>'`
- `<set>` is the Iconify collection name
- `<icon>` is the icon name inside that collection
