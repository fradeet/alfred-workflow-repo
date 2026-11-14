# Repository conventions

- Each `workflows/<name>/` directory is an independent project. Read its own `AGENTS.md`, if present, before editing it.
- Run project commands from that project's directory. Keep dependencies, versions, and packaging independent.
- Keep Alfred runtime files under `workflow/`. Preserve each project's language-specific source organization.
- Do not migrate template projects or import another workflow's implementation without an explicit task.
- Do not commit installed dependencies, user preferences, secrets, caches, or exported workflow packages.
- Preserve project licenses and third-party provenance.
- For changes, run the affected project's required checks. For plist changes, validate plist syntax, object connections, and external script paths.
- Existing import limitations are documented in `MIGRATION.md`; distinguish them from regressions introduced by a change.
- Do not move or delete original repositories or switch installed Alfred workflows as part of source maintenance unless requested.
