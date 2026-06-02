# Rim Zabarov Publications

Public repository for Rim Zabarov's publications, research notes, essays, working papers, data/code packages and media references.

## Purpose

This repository is the public publication layer. It should contain only materials intended for public reading or release:

- publication pages;
- working-paper pages;
- final or public PDFs;
- data/code release packages;
- English publication metadata;
- bibliography and media references.

Raw research work, private notes, source chats, exploratory handoff files and unpublished sensitive materials should remain in the private/local research workspace.

The commit boundary is documented in [PUBLICATION_BOUNDARY.md](PUBLICATION_BOUNDARY.md).
Public publication materials live under `publications/`, `data/`,
`assets/publications/` and `releases/`.

The machine-readable operating contract lives in [graph/graph.json](graph/graph.json).
It records the public/local boundary, publication lifecycle, full-text
mirroring policy and Docara assembly rules.

## Site

The repository is configured as a Docara-powered English publication site.

Local build commands:

```bash
/Applications/ServBay/bin/php vendor/bin/docara init --update
/Applications/ServBay/bin/php scripts/apply-docara-overrides.php
/Applications/ServBay/bin/php scripts/sync-publications-to-docara.php
PATH=/usr/local/bin:$PATH npm run prod
/Applications/ServBay/bin/php vendor/bin/docara build production
```

Repository operating-mode check:

```bash
npm run validate:graph
```

Notes:

- PHP is expected to run through ServBay on this machine.
- Frontend assets currently build with `/usr/local/bin/node` 14.17.1; Homebrew Node 25 is too new for this Laravel Mix stack.
- `webpack` is pinned to `5.74.0` for Laravel Mix 6 compatibility.
- Public markdown is stored in `publications/`.
- Production output is generated into `build_production/` and is not committed.

## Structure

```text
assets/
  publications/

publications/
  README.md
  series/
  venues/

releases/
  state-tone-index/

data/
  publications.yml
  publication-venues.yml
  article-roadmap.yml

graph/
  graph.json
  dna/
  specs/
  docs/

.github/workflows/
  docara-pages.yml
```

## First Research Project

- State Tone Index
- ORCID: https://orcid.org/0009-0004-8605-5594
- Author: Rim Zabarov
- Affiliation: Independent Researcher

## Publication Registry

- `data/publications.yml` stores publication records and research-output
  metadata.
- `data/publication-venues.yml` stores planned venues, languages, public URLs
  and cross-posting policy.
- `data/article-roadmap.yml` stores planned article cycles, target venues,
  language tracks and public-safety gates.
- `graph/` stores the machine-readable publication operating mode:
  repository zones, lifecycle, relations, evidence and publication policies.

Drafts and publication pages must stay public-safe: no private logs, customer
data, credentials, raw prompts, private graph evidence or unpublished scientific
claims.
