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
