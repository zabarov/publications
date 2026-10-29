# Article Completeness Checks

The publication registry separates a historical publication record from its
current platform availability. A removed article can remain archived with a
dated visibility note. Never use author-only access as proof of public access.

## Capture and Compare

Requirements: PHP with curl/DOM, Composer dependencies, and Pandoc on PATH.

```sh
php scripts/audit-publication-sources.php
php scripts/complete-publication-archives.php
```

The first command captures the 16 `recovered_snapshot` sources under ignored
local storage. It inventories article-body images, separately provided covers,
Habr previews, common attachment-link extensions and embedded media elements.
The second command compares the saved author text against the source after
normalizing presentation. It leaves tracked articles untouched without `--apply`.
Review every difference before proceeding. Platform tags and code-explanation
controls are metadata/UI, not author prose. Heading-like text inside code must
remain in the comparison.

If anonymous access fails, use the browser to read the exact article. Capture
only its author-content blocks, images and links, never account state, comments
or editor/submission payloads. A browser capture belongs in the same ignored
per-article workspace as `browser.json`; its schema is the source inventory plus
`html`, `source_kind` and an explicit `current_visibility` where needed. Preserve
earlier raw captures before replacing them. Unpublished changes require separate
author approval and cannot be treated as the historical published edition.

## Preserve

```sh
php scripts/complete-publication-archives.php --apply
php scripts/recover-publication-archives.php
php scripts/validate-publication-archive.php --require-completeness
```

Application refuses prose differences or a changed archive hash. Existing
article prose is retained. Available body images and platform covers use
content-hashed files with source URL and SHA-256 receipts. Platform previews are
stored separately as `preview.md`, not inserted into the article body.

Each article page links to original resources and records the preservation
boundary. A link to a video, service or code repository does not mean its whole
contents were archived. The file-extension inventory cannot prove the absence
of extensionless downloads. Record any such uncertainty rather than labeling
the whole Internet resource set complete.

The October 29 batch preserves 23 body/cover references across 16 articles:
9 earlier image files plus 14 added files. Twelve platform previews were added.
The Habr portrait article is an author-visible draft; its saved historical text
matches the visible author copy. No new draft text was published.

## Verify and Deliver

Run the archive/graph validators, check PHP syntax and Git whitespace, rebuild
Docara, and verify generated links and local media files. Inspect new images
for corrupt responses or unrelated content. Keep raw HTML and workflow evidence
ignored. Review the staged public diff, commit only the archive scope, push and
verify that the remote revision matches. A local-only ignored folder is not a
backup. Site deployment and social posting require their own authorization.
