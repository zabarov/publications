# Publication Archive Review

Reviewed: October 24, 2026.

## Result

Habr article 1098774 is preserved as published Russian Markdown, the deployed
JPEG cover and the approved PNG image master. Article 1044978's archive now
includes the continuation link. Both article records declare `full_mirror`
and point to files within this repository. Habr remains canonical.

## Repository Layout

The current layout is sufficient; additional top-level folders are unnecessary.

| Location | Responsibility | Preservation |
| --- | --- | --- |
| `data/publications.yml` | Published records, canonical URLs, text and cover paths | Git |
| `publications/articles/<venue>/<id>/` | Reader page and approved article text in its original language | Git |
| `assets/publications/<id>/` | Approved covers, image originals and provenance | Git |
| `data/article-roadmap.yml` | Public-safe topic plan | Git |
| `publications/placement/README.md` | Dated readable placement and queue snapshot | Git |
| Private workshop | Drafts, editorial decisions and working placement matrix | Separate workspace |
| Ignored `source/` | Raw captures, previous local captures and workflow evidence | Local only; separate backup required |

A commit records history locally. Verify a push before calling the public
archive remotely backed up. Ignoring a directory protects disclosure boundaries,
but provides no backup by itself.

## Corrections Applied

- Recorded federation part two as published and linked parts one and two.
- Corrected the outdated Habr metadata-only description.
- Separated English metadata from original-language article text.
- Added a readable placement matrix with published, deferred and unconfirmed
  placements distinguished.
- Refreshed the workshop's federation item and generated placement dashboard.
- Preserved previous local captures before archiver refreshes.
- Fixed Docara sync to include full article Markdown and local image files,
  rather than only README pages.
- Added an archive validator for duplicate IDs, declared text/cover files,
  provenance and venue references.
- Excluded local `work/` runtime records from public Git.

## Remaining Preservation Backlog

There are 20 published registry records: 2 declare public full-text archives;
18 do not. Thirteen remaining Habr records and the portrait Medium article
have local Markdown at their standard archive paths. Existence is confirmed;
completeness and media recovery still need review. DEV and three SSRN records
have no copy at the standard local path checked in this review. Other possible
copies have not been exhaustively searched.

Priorities:

1. Review the older local Habr copies and promote approved published text and
   assets into tracked article packages.
2. Preserve published DEV/Medium editions and research PDFs where access and
   author-approved release versions are confirmed.
3. Reverify the earlier HackerNoon and Medium editions recorded in the workshop,
   then add their records to the public registry. Their recorded URLs appear
   in the placement snapshot; this review does not confirm current availability.
4. Define a private backup destination for ignored drafts and raw captures.
   No backup service or private remote was configured by this update.

## Queue and Placement

The next core-series topic is graph context for skills. The workshop records a
brief, not a finished article. English adaptations remain deferred until the
Russian series is complete. Telegram delivery is unconfirmed. The old editorial
calendar contains historical planned weeks and should not be treated as a live
schedule. This review creates no new publication dates or external obligations.

Research-gated topics remain gated until their specific evidence and public
status are checked. The existence of an unrelated SSRN publication does not
automatically clear every graph article.

## Verification

```sh
php scripts/validate-publication-archive.php
npm run validate:graph
php scripts/sync-publications-to-docara.php
php vendor/bin/docara build
git diff --check
```

The public site was built locally. No Pages deployment or social posting is
part of this update.
