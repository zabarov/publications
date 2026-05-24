---
title: Publications Repository Docara Setup
status: completed
updated: 2026-05-24
---

# Publications Repository Docara Setup

## Goal

Create a public, English-first repository for Rim Zabarov's publications: scientific working papers, articles, essays, Russian and international publications, media references, and reproducible public research packages.

## Done When

- The repository has a clear public information architecture.
- Docara is installed and configured at the repository root.
- Source content lives in `source/docs/en`.
- Sensitive/private research materials stay outside this repository.
- The site can be built locally.
- GitHub Pages deployment is prepared.

## Current Scope

- Public repository: `/Users/rim/Documents/GitHub/publications`.
- Private research workspace remains separate: `/Users/rim/Documents/GitHub/science`.
- Initial research project exposed publicly: `State Tone Index`.

## Decisions

- Use English as the canonical public language.
- Keep raw research data and private drafts in the `science` repository.
- Publish only curated articles, working-paper pages, release packages, bibliographic metadata, and public-facing notes here.
- Use Docara as the publication site engine.
- Use ServBay PHP explicitly: `/Applications/ServBay/bin/php`.

## Progress

- Created public content structure under `source/docs/en`.
- Added `data/publications.yml` as a machine-readable publication index.
- Installed `simai/docara` via Composer using ServBay PHP.
- Ran `docara init --update` without overwriting existing publication pages.
- Applied branding: `Rim Zabarov Publications`.
- Updated Docara config metadata away from template defaults.
- Pinned `webpack` to `5.74.0` for Laravel Mix 6 compatibility.
- Built frontend assets successfully with `/usr/local/bin/node` 14.17.1.
- Built Docara production output successfully into `build_production/`.
- Added GitHub Pages Actions workflow at `.github/workflows/docara-pages.yml`.

## Remaining

- Enable GitHub Pages in the GitHub repository settings after the first push, using GitHub Actions as the source.
- Add actual public PDFs, released data/code packages, and publication metadata as they become ready.

## Next Step

Commit and push the repository, then enable GitHub Pages Actions deployment.
