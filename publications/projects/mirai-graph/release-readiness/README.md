# Mirai Graph Release-Readiness Audit

Audit date: 2026-06-12

Repository: <https://github.com/zabarov/mirai-graph>

## Verdict

Mirai Graph is ready for a public alpha explanation after the current local
documentation changes are reviewed.

The project already has enough public structure for a careful article: standard
sections, schemas, examples, profiles, pilots, release notes, CLI validation
commands and citation metadata.

## What Already Works

- The repository has a clear public alpha framing.
- The README explains the model, audiences and start paths.
- The project has MIT code licensing and CC-BY-4.0 documentation licensing.
- `CITATION.cff` exists.
- Release notes and roadmap exist.
- Examples, profiles, pilots and templates exist.
- CLI validation commands are documented.
- Full `npm test` passes.

## Validation Evidence

Passing command:

```bash
PATH=/Users/rim/.cache/codex-runtimes/codex-primary-runtime/dependencies/node/bin:$PATH npm test
```

Observed result:

- package validation passes;
- benchmark validation passes;
- profile validation passes;
- pilot validation passes;
- context-pack validation passes;
- release-state validation passes;
- positive and negative fixtures pass;
- playground and instrumentation reports validate.

## Findings

### Ready For Public Alpha Explanation

Mirai Graph can be explained publicly now, but the explanation should stay
inside the public alpha boundary:

- practical model;
- synthetic examples;
- public-safe pilots;
- schemas and validation;
- evidence and readiness as operating concepts.

### Needs Diff Review Before Announcement

The local repository contains documentation changes:

- README update;
- research evidence documentation updates;
- alpha.11 release note update;
- new evidence package v0.3 draft.

These do not block the audit result, but they should be reviewed before a final
announcement or release tag.

## Public Boundary

Public:

- model;
- standard sections;
- schemas;
- examples;
- public-safe pilots;
- profiles;
- templates;
- reproducibility notes;
- citation metadata.

Out of scope:

- private SIMAI routes;
- raw skill sources;
- internal logs;
- customer data;
- credentials;
- unpublished scientific claims.

## Next Goal

Prepare a focused public introduction to Mirai Graph through one practical
workflow.

Done means:

- one workflow is selected as the explanation path;
- the article explains state, relations, evidence, readiness and gates through
  that workflow;
- claims stay inside the public alpha boundary;
- the article points to a stable release candidate or reviewed repository state.

This goal connects to the final result because Mirai Graph is too broad to
introduce all at once. The first public article should make the model
understandable before inviting readers into the full standard.
