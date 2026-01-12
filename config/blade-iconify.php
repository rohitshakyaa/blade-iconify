<?php

return [
    /*
    | ----------------------------------------------------------------------
    | Prefix used when registering/using icons in Blade components.
    | ----------------------------------------------------------------------
    |
    | With `set_prefix` = 'rsi', your Blade component names will start with:
    |   <x-rsi-... />
    */
    'set_prefix' => 'rsi',

    /*
    | ----------------------------------------------------------------------
    | Where should generated SVGs be written/read from?
    | ----------------------------------------------------------------------
    |
    | This package can generate SVG files into:
    |
    |  - "package" : {package}/resources/svg (default)
    |  - "project" : {app}/resources/svg
    |  - "custom"  : any custom relative path from your project root
    |
    | IMPORTANT:
    | - This also controls where Blade Icons will look for the SVGs.
    | - For production, run: php artisan icons:cache
    */
    'export_to' => 'package', // package|project|custom

    // Only used when export_to = "custom" (relative to base_path()).
    'custom_path' => null,

    /*
    | ----------------------------------------------------------------------
    | Whitelist of icons to load/register.
    | ----------------------------------------------------------------------
    |
    | IMPORTANT:
    | - Only the icons listed here will be generated/loaded and made available in Blade.
    | - If an icon is NOT in this array, it will NOT be available as a Blade component.
    |
    | Usage example for the icon below:
    |   <x-rsi-material-symbols-light-10k-sharp />
    |
    | (Pattern: <x-{set_prefix}-{collection}-{icon-name} />)
    */
    'icons' => [
        // Material Symbols Light — "10k" icon (Sharp)
        'material-symbols-light:10k-sharp',
    ],
];
