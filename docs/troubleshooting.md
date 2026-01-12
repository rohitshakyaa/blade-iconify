# Troubleshooting

## 1) `SvgNotFound` / icon not found

Symptoms:

- `BladeUI\Icons\Exceptions\SvgNotFound`
- Or the extraction command prints: `Icon not found in set 'X': 'Y'`

Fix checklist:

- Ensure the icon ID exists: `<set>:<icon>`
- Ensure you generated SVGs:

```bash
php artisan iconify:extract-svgs --overwrite
```

- Confirm the SVG file exists in the expected folder and matches the name `{set}-{icon}.svg`
  - If you set `export_to=project` → `resources/svg`
  - If you set `export_to=package` → `vendor/rohitshakya/blade-iconify/resources/svg`

## 2) `Unable to locate set ...`

This means `Iconify\IconsJSON\Finder` couldn’t find the JSON for the set.

What to do:

- Ensure you have the Iconify JSON source installed/available for PHP.
- Confirm the set name is correct (Iconify collection name).

## 3) Icons not registering / components not found

The service provider registers a Blade Icons set pointing at a folder controlled by `config('blade-iconify.export_to')`:

- `package` → `vendor/rohitshakya/blade-iconify/resources/svg`
- `project` → `resources/svg`
- `custom` → `base_path(custom_path)`

Make sure:

- Composer autoload ran successfully
- Laravel package discovery is enabled, or you manually registered the service provider

Clear caches:

```bash
php artisan optimize:clear
```

## 4) I changed `set_prefix` but old components still resolve

Blade Icons caches icon sets during the request lifecycle.

Clear caches:

```bash
php artisan optimize:clear
```

Then re-run extraction if you also changed file naming conventions.


### ⚠ Important

If icons are missing or not updating, always run:

```bash
php artisan icons:clear
php artisan icons:cache
```