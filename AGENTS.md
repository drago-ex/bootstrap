# Agent Guide

Use this file as package-specific guidance when working in this repository. Keep changes aligned with the existing public API, project conventions, and supported PHP range.

## Package Overview
- **Package**: `drago-ex/bootstrap`
- **Type**: library
- **Purpose**: `ExtraConfigurator` extends Nette's `Configurator` with `addFindConfig()`, which finds `.neon` files in directories and caches the list in production.
- **PHP**: >=8.3 <9
- **Runtime dependencies**: nette/bootstrap (^3.2), nette/utils (^4.0), nette/caching (^3.2)
- **License**: MIT

## Repository Layout
- `src/`: package source code.
- `tests/`: automated tests; `tests/file/` holds the sample `.neon` files.
- `.github/`: CI workflows; keep their PHP versions and commands consistent with Composer and the package scripts.
- `composer.json`: package metadata, dependency constraints, autoloading, and available Composer scripts.

## Key Parts
- `addFindConfig($paths, ...$exclude)` finds `*.neon` with `Nette\Utils\Finder`; each exclude mask matches a whole file or directory name (projects exclude `Translate`, so translation files are not loaded as config).
- Files are added by file name compared numerically, then by path, so prefixes like `8.conf.neon` and `9.conf.neon` decide which configuration wins.
- The mode comes from `isDebugMode()`, not from Tracy, so callers set it before `addFindConfig()`. Debug mode searches on every call; otherwise the list is cached in the `config.search` namespace (`ExtraConfigurator::Caching`) under a hash of the paths and exclusions, so different calls never share an entry (install and web containers search different directories in the same temp directory). The entry does not expire; new files in production need a cleared `temp/cache`.
- Projects (`drago-ex/project`, `project-install`, `generator-cli`) create it in `app/Bootstrap.php`; `bootConfigurator()` there is an integration hook for tools such as `nette/mcp-inspector`, not a requirement.

## Dependencies

Composer constraints describe the package contract. Before changing integration behavior, inspect the source and the actual call sites in this package; do not infer runtime usage solely from dependency declarations.
Development dependencies include nette/tester (^2.5), phpstan/phpstan-nette (^2.0), tracy/tracy (^2.10) (tests check that the mode does not come from Tracy).

## Essential Commands

```bash
composer tester                 # tester tests -s -C
composer phpstan                # phpstan analyse
```

## Conventions & Rules

- Preserve backward compatibility unless the task explicitly permits a breaking change. Check callers and public signatures before changing behavior.
- Follow the existing PHP style: strict types, explicit parameter and return types, tabs for indentation, and single-quoted strings where practical. Match nearby code when a convention is unclear.
- Add or update focused tests for behavioral changes when this package has a runnable test setup. Do not invent test commands; use only the scripts and configuration present here.
- Update README examples and Composer metadata when a public API or supported requirement changes.
