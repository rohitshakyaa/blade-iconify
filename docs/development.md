# Development (local)

## Repo structure

- `src/` — service provider + command
- `config/` — config file
- `resources/svg/` — generated SVGs (commonly gitignored in real-world usage)
- `docs/` — documentation

## Typical workflow

1. Add icon IDs to config
2. Run extraction:

```bash
php artisan iconify:extract-svgs --overwrite --optimize
```

3. Use icons in Blade.

## Contributing

- Keep changes focused and well-tested
- Update docs when behavior changes
