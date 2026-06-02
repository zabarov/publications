# Public Publication Boundary

This repository is intended to be public. Treat every committed file as visible
to readers, indexers, platform editors and future collaborators.

## Public by Default

These materials may be committed when they are public-safe:

- final or public-safe article drafts;
- publication pages under `publications/`;
- publication metadata under `data/`;
- public assets under `assets/publications/`;
- final public PDFs, release packages and reproducibility materials;
- sanitized workflow files under `workflow/` that describe repository work
  without private operational detail;
- build configuration, dependency manifests and GitHub Actions workflows.

## Keep Out of Git

These materials must stay local or in a separate private workspace:

- credentials, tokens, cookies, private keys, `.env` files and API secrets;
- customer data, private SIMAI logs, raw operational traces and support cases;
- raw prompts, private skill internals, safe-write policies and hidden routing
  details;
- unpublished scientific evidence, private research drafts and sensitive
  methodology notes;
- drafts currently under moderation, reviewer comments, editor correspondence
  and platform-specific submission copies unless they are explicitly cleared
  for public release;
- local Codex thread context, unredacted handoffs, screenshots with private UI
  data and temporary exports;
- generated dependency folders, build output, cache folders and QA artifacts.

## Recommended Public Structure

```text
data/
  publications.yml          # published records and research-output metadata
  publication-venues.yml    # venue registry and language/cross-posting policy
  article-roadmap.yml       # public-safe article roadmap and gates

publications/
  README.md                 # public publication index
  series/                   # public article cycles
  venues/                   # public venue registry
  russian/<venue>/<slug>/   # Russian publication track
  international/<venue>/    # English/international tracks

assets/publications/
  <publication-id>/         # public images and downloadable assets

releases/
  <project-id>/             # final public release packages

workflow/
  *.md                      # sanitized workflow records only
```

## Suggested Private Local Structure

The following ignored paths may be used locally. They are not part of the public
repository structure:

```text
private/
drafts/private/
notes/private/
research/private/
source/drafts/in-progress/
source/drafts/on-moderation/
source/drafts/returned/
source/drafts/archive/
source/submissions/<venue>/
source/reviews/
exports/
rendered/
qa-output/
```

## Draft Moderation Workflow

Use `source/drafts/on-moderation/<article-id>/` for the exact draft that is
currently waiting for platform moderation. A typical local-only package may
contain:

```text
source/drafts/on-moderation/<article-id>/
  draft.md                  # exact submitted text
  metadata.yml              # venue, submitted_at, language, status
  submission-notes.md       # local notes and reviewer/editor context
```

The public repository should contain only a safe status card, for example:

```text
publications/russian/habr/<article-id>/README.md
data/article-roadmap.yml
```

Public metadata may say `status: submitted` or `status: on-moderation`, but the
full submitted text should stay in `source/` until it is accepted or explicitly
cleared for public release.

## Publication Rule

Before committing a new article, ask:

1. Would this be acceptable if opened directly on GitHub?
2. Does it reveal private SIMAI implementation, customer context or raw
   operating traces?
3. Does it make research claims that are not yet public through SSRN, a paper,
   or another approved research artifact?
4. Does it have the right canonical/source-link plan for cross-posting?

If the answer is uncertain, keep the material in a private ignored path and
publish only a sanitized page or metadata entry.
