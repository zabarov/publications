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

That batch left four records without archived text. A subsequent browser
recovery on October 24 resolved all four: published SSRN PDFs were downloaded
directly from their paper pages, and the Medium article's author-content blocks
and cover were captured from the live page. No local draft was substituted for
a downloaded published paper.

The current inventory is **22 published records, all with archived text**:
6 verified text/media or PDF packages and 16 recovered historical snapshots.
The SSRN PDFs contain 10, 7 and 12 pages respectively. Their extracted text is
identical to the previously submitted local PDF versions, although PDF binary
hashes differ. The actual SSRN downloads are retained unchanged.

SSRN titles, posted dates, page counts and DOI links were checked in the browser.
The placeholder title for EOG 6841618 is resolved. The GoK-8 published PDF still
contains the author's original draft-status line on its title page; archiving
retains it unchanged. This note records a source artifact, not a change to the
paper's verified public availability.

Medium displays June 9 for the imported process article, while the prior author
workflow records publication on June 15. Both facts are distinguished. Its
canonical URL still points to DEV. All 91 observed author-content blocks and
one body image are preserved; platform subscription controls and avatars are
excluded.

Priorities:

1. Review original-page image completeness for the 16 recovered historical copies.
2. Define a private backup destination for ignored drafts and raw captures.
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

`--only=<publication-id>` scopes an explicit recovered-package refresh to one
record while still regenerating the complete inventory. Existing canonical URLs
are preserved, including imported mirrors whose canonical source is elsewhere.

`php scripts/preserve-ssrn-pdf.php <abstract-id> <downloaded-pdf>` preserves a
browser-downloaded published PDF after checking the registry, captured public
page title, PDF title/author and page count. It also extracts every page into
Markdown using Poppler. Page boundaries and preformatted tables are retained;
the PDF is authoritative for typography. An existing different PDF or Markdown
is protected from replacement. Public manifests contain URLs and hashes rather
than private workspace paths or submission correspondence.

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
