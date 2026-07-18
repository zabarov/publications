# Docara Publish Checklist

This checklist prepares the publication site for GitHub Pages without changing
live settings or pushing from local automation.

## Current Status

- Local Docara preview is ready in `build_local/`.
- Habr, DEV Community, Medium and SSRN pages are generated from public
  repository content.
- Public project pages for SitePack and Mirai Graph are generated from public
  repository content.
- SitePack and Mirai Graph release-readiness and publication-kit pages are
  generated from public repository content.
- GitHub Pages workflow exists at `.github/workflows/docara-pages.yml`.
- The workflow is manual-only through `workflow_dispatch`.
- No GitHub Pages deployment has been triggered from this checklist.

## Local Verification

Run from the repository root:

```bash
php scripts/sync-publications-to-docara.php
YARN_IGNORE_PATH=1 npx --yes yarn@1.22.22 --no-default-rc install --frozen-lockfile --production=false --non-interactive
YARN_IGNORE_PATH=1 npx --yes yarn@1.22.22 --no-default-rc dev
php vendor/bin/docara build
YARN_IGNORE_PATH=1 npx --yes yarn@1.22.22 --no-default-rc validate:graph
```

Use the committed Yarn lockfile; Vite is the only asset build path.

Verify generated content:

```bash
rg -n "Habr articles|DEV Community|Medium articles|SSRN preprints|SitePack|Mirai Graph|1044978|dev-to-1055|9b909c8e1241|6823458|6823819" build_local source/docs/en/publications
```

Expected generated local pages:

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

## GitHub Pages Launch

Before launch:

- confirm `data/publications.yml` contains only public-safe records;
- confirm `data/projects.yml` contains only public-safe project records;
- confirm `source/`, `build_local/`, `build_production/`, `.env` and
  dependency directories remain ignored;
- confirm the workflow is still manual-only if accidental deploys are not
  allowed;
- commit the public registry, public pages, scripts and workflow changes;
- push only after reviewing the final diff.

Repository settings required on GitHub:

- Pages source: GitHub Actions;
- workflow: `Publish Docara Site`;
- branch: current default branch.

Manual launch:

1. Push the reviewed commit.
2. Open GitHub Actions.
3. Run `Publish Docara Site` manually.
4. Check the Pages deployment URL.
5. Smoke-check:
   - home page;
   - publications index;
   - Habr page;
   - DEV Community page;
   - Medium page;
   - SSRN page;
   - SitePack page;
   - SitePack release-readiness page;
   - SitePack publication-kit pages;
   - Mirai Graph page;
   - Mirai Graph release-readiness page;
   - Mirai Graph publication-kit pages;
   - Skills as Expert Systems page.

## Rollback

If the deployed site is wrong:

1. Disable or stop further workflow runs.
2. Revert the public registry/page changes in git.
3. Push the rollback commit.
4. Run the workflow manually again, or temporarily disable Pages if needed.

Generated directories are reproducible and should not be committed:

```text
build_local/
build_production/
source/
```
