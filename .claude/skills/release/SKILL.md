---
description: >
  Release a new version of phortugol/phortugol. Use when the user asks to release,
  publish, bump version, or tag a new version. Follows semver and Conventional Commits.
disable-model-invocation: false
---

# Release Checklist

## Step 1 — Determine the version bump

Read the commits since the last tag:

```bash
git log $(git describe --tags --abbrev=0)..HEAD --oneline
```

Apply semver rules:
- Any `BREAKING CHANGE` footer or `!` type → **major**
- Any `feat` → **minor**
- Only `fix`, `perf`, `refactor`, `docs`, `chore` → **patch**

## Step 2 — Run the full quality check

```bash
composer check
```

All three steps (format check, static analysis, tests) must pass. Fix any failure before continuing.

## Step 3 — Update CHANGELOG.md

Add a new section at the top, below the `# Changelog` heading:

```markdown
## [X.Y.Z] - YYYY-MM-DD

### Added
- ...

### Fixed
- ...

### Changed
- ...

### Breaking Changes
- ...
```

Only include sections that have entries. Use the commit messages as source.

## Step 4 — Bump the version

There is no version field in `composer.json` — Packagist reads the git tag.
Skip this step if the project does not maintain a `VERSION` file.

## Step 5 — Commit and tag

```bash
git add CHANGELOG.md
git commit -m "chore: release vX.Y.Z"
git tag -a vX.Y.Z -m "Release vX.Y.Z"
```

## Step 6 — Push

```bash
git push origin main --tags
```

Packagist will pick up the new tag automatically via the GitHub webhook.

## Step 7 — Verify on Packagist

Wait ~60 seconds, then check:
`https://packagist.org/packages/phortugol/phortugol`

The new version should appear under the "Releases" section.
