# Contributing Guide

## Changelog Contribution Guidelines

To ensure a consistent and readable changelog, follow these rules for every version release:

### Required Format

Every version entry **must include all of the following sections**, even if empty:

- **Added** — New features or functionality
- **Changed** — Modifications to existing features or behavior
- **Fixed** — Bug fixes or patches
- **Documentation** — Updates to project docs or inline code comments
- **Contributor(s)** — GitHub usernames or names of those involved

> Use `- None` under sections that have no updates for that release.

### Placement Rules

- Add your changes under the `## [Unreleased]` section until the release is tagged.
- When releasing a version, rename `Unreleased` to `[x.y.z] - YYYY-MM-DD` and **move it to the top** of the file.
- Older releases are pushed downward, keeping the newest on top.

---

### Example

```markdown
## [1.2.0] - 2025-06-15

### Added
- Bulk export queueing system.

### Changed
- None

### Fixed
- None

### Documentation
- Updated export configuration examples.

### Contributor(s)
- @janedoe
