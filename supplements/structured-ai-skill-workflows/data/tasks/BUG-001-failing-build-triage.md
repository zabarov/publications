# BUG-001: Failing Build With Environment Uncertainty

## Task Facts

A user reports that a local build fails after dependency updates.

Available facts:

- error excerpt says `Module not found: @internal/ui-kit`;
- lockfile changed in the same branch;
- CI status is unknown;
- the local package manager version is unknown;
- the repository has a monorepo workspace configuration.

The user asks: "Fix it."

## Known Risks

- missing package may be workspace resolution, registry access or lockfile issue;
- CI status is unknown;
- local toolchain version may differ;
- fixing by installing a different package may mask the real workspace problem.

## Expected Evidence

- package manager and version;
- workspace config;
- lockfile diff;
- CI or reproducible local command output.

## Expected Output Qualities

- should triage before changing dependencies;
- should ask for or run reproducible checks if available;
- should distinguish local environment issue from repository issue;
- should produce a safe next diagnostic command list.
