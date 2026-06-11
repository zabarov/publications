# Portfolio Site Final Review

Review date: 2026-06-12

## Verdict

The local portfolio site is ready for final human review before a separate
GitHub Pages launch decision.

No deploy, commit, push or GitHub Pages settings change was performed during
this review.

## Current Result

The repository now has a public portfolio structure for:

- article records: Habr, DEV Community, Medium and SSRN;
- public projects: SitePack and Mirai Graph;
- SitePack release-readiness audit;
- SitePack article and release-note draft package;
- Mirai Graph release-readiness audit;
- Mirai Graph article draft package;
- Skills as Expert Systems article cycle.

## What This Gives

The repository is no longer only a machine-readable registry.

It now works as a public portfolio:

- readers can see published articles;
- readers can understand public projects without reading full specifications;
- future articles have stable project pages to link to;
- local raw/source material remains outside the public commit boundary;
- Docara can generate a static site from public-safe Markdown pages.

## Local Verification

Passed:

- YAML parse for publication data files;
- Docara sync from `publications/` into ignored `source/docs/en`;
- local Docara build into ignored `build_local`;
- publication graph validation;
- `git diff --check`;
- static checks for generated publication, project and publication-kit pages;
- workflow is manual-only through `workflow_dispatch`;
- no tracked `source/`, `.env`, private-key-like files or generated build
  directories.

Known limitation:

- a direct keyword scan for secret-like words was blocked by the local safety
  hook to avoid accidental sensitive-value output. Path-level sensitive tracking
  checks passed.

## Generated Pages Checked

Expected local pages include:

```text
build_local/en/publications/articles/index.html
build_local/en/publications/articles/habr/index.html
build_local/en/publications/articles/dev-to/index.html
build_local/en/publications/articles/medium/index.html
build_local/en/publications/articles/ssrn/index.html
build_local/en/publications/projects/index.html
build_local/en/publications/projects/sitepack/index.html
build_local/en/publications/projects/sitepack/release-readiness/index.html
build_local/en/publications/projects/sitepack/publication-kit/index.html
build_local/en/publications/projects/sitepack/publication-kit/introductory-article/index.html
build_local/en/publications/projects/sitepack/publication-kit/release-note-v0.4.0-draft/index.html
build_local/en/publications/projects/mirai-graph/index.html
build_local/en/publications/projects/mirai-graph/release-readiness/index.html
build_local/en/publications/projects/mirai-graph/publication-kit/index.html
build_local/en/publications/projects/mirai-graph/publication-kit/introductory-article/index.html
build_local/en/publications/series/skills-as-expert-systems/index.html
build_local/en/publications/venues/index.html
```

## Launch Boundary

The local track is complete when this review is accepted.

Actual public launch is a separate decision because it requires:

- selecting the exact commit scope;
- committing reviewed public files;
- pushing to GitHub;
- confirming GitHub Pages source as GitHub Actions;
- running the manual `Publish Docara Site` workflow;
- checking the deployed URL.

## Remaining Risk Before Launch

- The working tree contains changes from several previous batches. The launch
  commit should be scoped intentionally.
- SitePack itself still has release-cleanup items before the SitePack article or
  release note should be published externally.
- Mirai Graph has local documentation changes in its own repository that should
  be reviewed before an external announcement.
- Browser visual QA was not performed in this environment; static output checks
  passed.

## Next Goal

The next goal is the live publication decision.

Done means:

- the final diff is reviewed;
- the launch commit scope is selected;
- the commit is created;
- the branch is pushed;
- the manual GitHub Pages workflow is run;
- the deployed site is smoke-checked.

This goal is separate from local site preparation because it changes remote
state.
