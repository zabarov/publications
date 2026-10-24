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

Follow-up recovery completed on the same date: 22 published registry records
now include 2 verified text/cover archives and 16 recovered text snapshots.
Thirteen older Habr copies and the portrait Medium copy were promoted from
local storage. DEV Markdown was retrieved through its public article API;
HackerNoon text was converted from the published article HTML with Pandoc.
Nine referenced images were downloaded, checked as images and assigned hashes.
The earlier HackerNoon and Medium placements are now in the registry.

Recovered snapshots preserve the available published prose without editorial
rewriting. Their correspondence with the latest online revision and original
media completeness remain unverified. A snapshot containing zero image links
does not prove the original article contained no images. The original local
sources were retained, including article blockquotes and code examples.

Four records still lack public archived text: three SSRN preprints and the
Medium edition of the process article. HTTP 403 blocked the Medium edition
and SSRN 6841618 in this follow-up; the other SSRN records retain their earlier
access-blocked status. Drafts were not substituted for published research PDFs.
Other possible research copies have not been exhaustively searched.

Priorities:

1. Obtain the exact published SSRN PDFs and the unavailable Medium edition
   through an accessible public page or an author-provided export.
2. Review original-page image completeness for the recovered historical copies.
3. Define a private backup destination for ignored drafts and raw captures.
   No backup service or private remote was configured by this update.

The readable [preservation inventory](../publications/articles/preservation.md)
is generated from the registry. Per-article `preservation.json` files record
source kind, capture date when known, text hash and each inspected image URL.
The archive validator checks those text/image hashes. It deliberately keeps
recovered snapshots separate from fully verified mirrors.

## Recovery Tools

`php scripts/recover-publication-archives.php` promotes missing public packages
from locally retained, previously published Markdown and refreshes the readable
inventory and venue indexes. Run it only on reviewed author-approved sources.
It does not fetch missing article bodies or prove media completeness.
`--refresh-generated` explicitly rebuilds recovered packages; it refuses to
overwrite article text changed since the last recorded hash. Raw sources remain
untouched. Review and commit the resulting diff after validation.

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
