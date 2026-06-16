# Public Publication Boundary

This repository is intended to be public. Treat every committed file as visible
to readers, indexers, platform editors and future collaborators.

The machine-readable version of this boundary is stored in `graph/`. Start with
`graph/graph.json` and `graph/docs/guides/operating-mode.md` when a workflow
needs a structured contract instead of prose only.

## Public by Default

These materials may be committed when they are public-safe:

- final or public-safe article drafts;
- publication pages under `publications/`;
- publication metadata under `data/`;
- public assets under `assets/publications/`;
- final public PDFs, release packages and reproducibility materials;
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

graph/
  graph.json                # graph manifest and operating contract entrypoint
  specs/                    # repository zones, policies, lifecycle, relations
  docs/guides/              # human-readable graph guides

publications/
  README.md                 # public publication index
  series/                   # public article cycles
  venues/                   # public venue registry

assets/publications/
  <publication-id>/         # public images and downloadable assets

releases/
  <project-id>/             # final public release packages
```

## Local Work

Ignored local workspaces may be used for drafts, submissions, reviewer notes,
rendered previews and temporary exports. They are not part of the public
repository structure and should not be documented in the public README.

```text
private/
source/
exports/
rendered/
qa-output/
```

When a new external article is published, save a local recovery copy under
`source/articles/<venue>/<id>/` before treating the public canonical URL as the
only source of truth. For Habr articles, use:

```bash
php scripts/archive-habr-source.php https://habr.com/ru/articles/<id>/
```

## Draft Moderation Workflow

Use ignored local workspace files for exact drafts that are currently waiting
for platform moderation.

The public repository should contain only a safe status card, for example:

`data/article-roadmap.yml`, and only create an article folder under
`publications/` when the status is public-safe enough to show.

Public metadata may say `status: submitted` or `status: on-moderation`, but full
submitted text should stay in ignored local files until it is accepted or
explicitly cleared for public release.

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

## Graph Validation

Run this check before committing changes that affect publication structure,
metadata, full-text mirrors or public/local boundaries:

```bash
npm run validate:graph
```
