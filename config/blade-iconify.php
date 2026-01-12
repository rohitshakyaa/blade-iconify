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
