# To My Agents!

It is my fervent wish that this file guide every AI coding agent working with code in this repository.

## Package Overview
- **Package**: `drago-ex/bootstrap`
- **PHP Version**: 8.3+ (< 9)
- **License**: MIT
- **Dependencies**: `nette/bootstrap`, `nette/utils`, `nette/caching`, `tracy/tracy`
- **Purpose**: Extends the standard Nette `Configurator` with `Drago\Bootstrap\ExtraConfigurator`, which automatically discovers `.neon` configuration files across directories (`addFindConfig()`) and caches discovery results in production.

## Architecture & How It Works
1. **Inheritance**: `ExtraConfigurator` inherits directly from `Nette\Bootstrap\Configurator`.
2. **Config Discovery (`addFindConfig`)**:
   - Uses `Nette\Utils\Finder::findFiles('*.neon')`.
   - Supports excluding files/directories using variadic parameter `...$exclude`.
   - In development mode (`Debugger::$productionMode === false`), found configs are loaded immediately and the cache is cleared every time to reflect changes live.
   - In production mode, the list of discovered files is stored in cache under key `config.search` (`ExtraConfigurator::Caching`) using `Nette\Caching\Storages\FileStorage`.
3. **Workspace integration with `nette/mcp-inspector`**:
   - The `project` and `project-test` workspace applications expose `bootConfigurator(): ExtraConfigurator` so the inspector can obtain the configured instance before container creation.
   - This method is an integration hook for those applications, not a requirement for ordinary use of `ExtraConfigurator`; see `readme.md` for the standalone usage pattern.

## Essential Commands
Always run static analysis and tests before submitting non-trivial changes:

```bash
# Run tests via Nette Tester
composer tester                      # runs: tester tests -s
vendor/bin/tester tests -s -C        # direct tester execution with colored output

# Static analysis
composer phpstan                     # PHPStan analysis configured level
```

## Test Suite Structure
- Test bootstrap: `tests/bootstrap.php` (initializes `Tester\Environment`, temporary directory `tests/tmp`, and `ExtraConfigurator`).
- Test fixtures: `tests/file/` (contains subdirectories `one` and `two` with sample `.neon` files).
- Test helper class: `tests/ConfigCache.php` (inspects and purges discovery cache from `tests/tmp/cache`).
- Tests: `tests/Bootstrap/ExtraConfigurator.phpt` (verifies single-directory scan, multi-directory scan, and exclusion filters).

## Conventions & Rules
- Every PHP file **must** start with `declare(strict_types=1);`.
- Use **tabs** for indentation (not spaces).
- Strict typing across all properties, arguments, and return types.
- Single quotes `'` for strings unless variable interpolation or apostrophes are needed.
- Preserve all existing comments, docstrings, and documentation integrity.
