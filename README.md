# Rim Zabarov Publications

Public repository for Rim Zabarov's publications, research notes, essays, working papers, data/code packages and media references.

## Purpose

This repository is the public publication layer. It should contain only materials intended for public reading or release:

- publication pages;
- working-paper pages;
- final or public PDFs;
- data/code release packages;
- English summaries of Russian-language publications;
- bibliography and media references.

Raw research work, private notes, source chats, exploratory handoff files and unpublished sensitive materials should remain in the private/local research workspace.

## Docara Site

The repository is configured as a Docara-powered English publication site. Source pages live under:

```text
source/docs/en/
```

Local build commands:

```bash
/Applications/ServBay/bin/php vendor/bin/docara init --update
PATH=/usr/local/bin:$PATH npm run prod
/Applications/ServBay/bin/php vendor/bin/docara build production
```

Notes:

- PHP is expected to run through ServBay on this machine.
- Frontend assets currently build with `/usr/local/bin/node` 14.17.1; Homebrew Node 25 is too new for this Laravel Mix stack.
- `webpack` is pinned to `5.74.0` for Laravel Mix 6 compatibility.
- Production output is generated into `build_production/` and is not committed.

## Structure

```text
source/docs/en/
  index.md
  about.md
  publications/
  research/
  bibliography/
  media/

assets/
  publications/

releases/
  state-tone-index/

data/
  publications.yml

.github/workflows/
  docara-pages.yml
```

## First Research Project

- State Tone Index
- ORCID: https://orcid.org/0009-0004-8605-5594
- Author: Rim Zabarov
- Affiliation: Independent Researcher
