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
   - The mode comes from `$this->isDebugMode()`, not from Tracy, so callers must set it before `addFindConfig()`.
   - In debug mode, the directories are searched on every call and nothing is cached.
   - Otherwise, the list of discovered files is cached in the `config.search` namespace (`ExtraConfigurator::Caching`) using `Nette\Caching\Storages\FileStorage`. The key is a hash of the paths and exclusions, so different calls never share an entry (the `cms` application relies on this: its install and web containers search different directories in the same temp directory). The entry does not expire; new files in production need a cleared `temp/cache`.
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
- Test bootstrap: `tests/bootstrap.php` (initializes `Tester\Environment` with `test()` functions, the `tests/tmp` directory, the `TestConfigurator` helper exposing the collected config names, and `createConfigurator()`, which gives every test its own temp directory unless one is passed).
- Test fixtures: `tests/file/` (contains subdirectories `one` and `two` with sample `.neon` files).
- Tests: `tests/Bootstrap/ExtraConfigurator.phpt` (single and multiple directories, excluding a directory and a file, separate cache entries per paths and exclusions, production caching, debug mode without caching, and the mode taken from the configurator rather than Tracy).

## Conventions & Rules
- Every PHP file **must** start with `declare(strict_types=1);`.
- Use **tabs** for indentation (not spaces).
- Strict typing across all properties, arguments, and return types.
- Single quotes `'` for strings unless variable interpolation or apostrophes are needed.
- Preserve all existing comments, docstrings, and documentation integrity.
