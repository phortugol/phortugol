# Contributing to Phortugol

Thank you for your interest in contributing. This document covers everything you need to get started.

## Setup

```bash
git clone git@github.com:phortugol/phortugol.git
cd phortugol
ddev start                # starts the container and runs composer install
```

## Before Submitting a PR

```bash
composer format           # fix code style
composer analyse          # static analysis — must pass at level 8
composer test             # all tests must pass
```

All three must pass cleanly. CI will reject PRs that fail any of them.

---

## Commit Convention

This project follows **Conventional Commits** ([conventionalcommits.org](https://www.conventionalcommits.org)).

### Format

```
<type>(<scope>): <description>

[optional body]

[optional footer]
```

### Types

| Type | When to use |
|---|---|
| `feat` | A new feature or language construct support |
| `fix` | A bug fix |
| `perf` | A performance improvement |
| `refactor` | Code change that is neither a fix nor a feature |
| `test` | Adding or correcting tests |
| `docs` | Documentation only |
| `chore` | Tooling, config, dependencies |
| `ci` | CI/CD pipeline changes |

### Scopes

| Scope | Covers |
|---|---|
| `lexer` | `Phortugol\Lexer\` |
| `parser` | `Phortugol\Parser\` |
| `interpreter` | `Phortugol\Interpreter\` |
| `runtime` | `Phortugol\Runtime\` |
| `exceptions` | `Phortugol\Exceptions\` |

### Examples

```bash
feat(lexer): add support for single-line comments (//)
fix(interpreter): throw RuntimeException on division by zero
feat(parser): support para/fimpara loop
test(interpreter): add coverage for while loop guard
refactor(runtime): rename ExecutionDriver to Runtime
docs: add CONTRIBUTING.md
chore: update phpstan to v2
```

### Rules

- Use the **imperative mood**: "add support" not "added support"
- Do not capitalize the first letter of the description
- Do not end the description with a period
- Keep the description under 72 characters
- Reference issues in the footer: `Closes #42`

### Breaking Changes

Add `!` after the type/scope and a `BREAKING CHANGE:` footer:

```
feat(runtime)!: rename ExecutionDriver to Runtime

BREAKING CHANGE: The ExecutionDriver interface has been renamed to Runtime.
Update all implementations to implement Phortugol\Runtime\Runtime.
```

---

## Pull Request Guidelines

- One PR per feature or fix
- PRs must have a descriptive title following the commit convention
- Include tests for any new behavior
- Update `CHANGELOG.md` if applicable

## Reporting Issues

Open a GitHub issue with:
- The Portugol source that caused the problem
- The expected output
- The actual output or exception message
- PHP version (`php --version`)
