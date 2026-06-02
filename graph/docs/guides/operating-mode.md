# Publication Operating Mode

This repository has three working layers.

## Public Portfolio Layer

Tracked files under `publications/`, `data/`, `assets/publications/`, `releases/`
and `graph/` are public. Treat them as visible to readers, search engines,
platform editors and future collaborators.

Use this layer for:

- publication cards and indexes;
- public metadata;
- canonical links;
- approved summaries and excerpts;
- public release packages;
- machine-readable operating contracts.

## Local Source Layer

`source/` is ignored by Git. Use it for raw and editorial work:

- full local article copies;
- drafts in progress;
- moderation submissions;
- reviewer and editor notes;
- temporary Docara source;
- workflow and action-gate evidence.

Do not promote files from `source/` directly. Promote only curated,
public-safe outputs after applying the relevant policy.

## Docara Assembly Layer

Docara pages may be generated from public metadata and approved article text.
The default for external publications is `canonical_only`: show the portfolio
card and link to the original publication.

Before full mirroring, set an explicit per-publication policy such as
`full_mirror` or `adapted_version`.

## Validation

Run:

```bash
npm run validate:graph
```

The validator checks that the graph is present, JSON files are valid, `source/`
is ignored and no `source/` files are tracked.
