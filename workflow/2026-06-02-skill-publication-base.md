---
title: Skill Publications Base
status: in-progress
updated: 2026-06-02
---

# Skill Publications Base

## Goal

Create a public-safe publication base for articles about expert skills, skill
consiliums, SIMAI skill federation and practical GrowGraph usage.

## Done When

- The repository has a machine-readable registry of planned publication venues.
- The repository has a machine-readable roadmap of planned articles.
- Public publication materials live outside ignored `source/`.
- Sensitive internal federation details, raw logs, private customer data and
  unpublished scientific claims remain outside the public repository.

## Batches

### Batch 1 - Repository Base

Status: completed on 2026-06-02.

- Added `data/publication-venues.yml`.
- Added `data/article-roadmap.yml`.
- Added public publication pages under `publications/`.

### Batch 1.1 - Public Boundary And Ignore Rules

Status: completed on 2026-06-02.

- Added `PUBLICATION_BOUNDARY.md`.
- Expanded `.gitignore`.
- Aligned repository rule: `source/` is ignored raw/local workspace, while
  public materials live in `publications/`, `data/`, `assets/publications/`,
  `releases/` and `workflow/`.

### Batch 1.2 - Moderation Draft Workspace

Status: completed on 2026-06-02.

- Defined local-only moderation draft workspace under `source/drafts/`.
- Added guidance for `source/drafts/on-moderation/<article-id>/`.
- Clarified that public repository should expose only safe status metadata while
  a draft is under moderation.

### Batch 1.3 - Repository Cleanup

Status: completed on 2026-06-02.

- Added `scripts/sync-publications-to-docara.php`.
- Updated GitHub Pages workflow to build Docara source from public
  `publications/` files.
- Removed tracked `source/` files from Git index while keeping local files.

### Batch 2 - First Article Drafts

Status: proposed.

- Draft the first Russian Habr article about expert skill consiliums.
- Prepare a shorter English adaptation after the Russian draft stabilizes.
- Add publication records after each external URL exists.

## Gates

- Do not publish private SIMAI operational logs, customer data, credentials,
  internal prompts, private graphs, unpublished research evidence or raw skill
  safe-write rules.
- Do not present evolutionary graph research as public-scientific material until
  the relevant SSRN/paper gate is passed.
- Use canonical links when cross-posting where the platform supports them.

## Lessons Learned

- `source/` must not be treated as public content in this repository family; it
  is the raw/local workspace.
- Moderation drafts are not the same as public drafts: the exact submitted text,
  editor notes and platform-specific copies stay in ignored `source/`.
- Public pages need a separate top-level namespace, currently `publications/`.
- There are two public boundaries: rendered site visibility and GitHub
  repository visibility.
- Ignoring `source/` is not sufficient after files were already tracked; cleanup
  also requires `git rm --cached -r source` plus a build sync path.

## Process Improvements

- Add every new publication idea to `data/article-roadmap.yml` before drafting.
- Keep private article exploration in ignored paths until the draft is safe
  enough to become a public page or public metadata entry.
- Represent moderation state publicly as metadata, not as the full submitted
  article text.
- Keep the build source materialization step explicit in CI so future readers
  understand why `source/` is absent from the repository.

## Follow-up Proposals

- Add a lightweight validator for venue IDs and article IDs in `data/*.yml`.
- Decide whether to generate a richer public site navigation from
  `publications/` metadata.

## Next Step

Draft article 1 in ignored `source/drafts/in-progress/`, then promote only the
public-safe card and metadata to `publications/` and `data/`.
