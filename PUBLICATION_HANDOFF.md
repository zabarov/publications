# Publication Handoff

This repository is the public archive and registry for final public-safe
materials. It is not the workshop where raw ideas, drafts, private notes or
moderation copies are developed.

## Accepted Inputs

Accept materials only when they are explicitly public-safe:

- final article page or public-safe full text;
- publication metadata;
- canonical URL and cross-posting notes;
- public cover or article assets;
- links to public source artifacts, demo repositories, papers or releases;
- public-safe series or venue metadata;
- metrics that are safe to show publicly.

## Required Metadata

For each publication record, collect:

- stable publication id;
- title;
- language;
- type: article, essay, working paper, release, media, talk or series page;
- status: planned, submitted, published, mirrored, archived or withdrawn;
- venue;
- public URL;
- canonical URL decision;
- publication date when known;
- source project or workshop handoff reference when safe to name;
- tags/topics;
- public-safe summary.

## Keep Out

Do not bring these materials into this repository:

- raw workshop drafts;
- private notes, chats, transcripts and handoff files;
- platform moderation drafts unless explicitly cleared;
- reviewer/editor correspondence;
- internal SIMAI processes, prompts, routes, graph internals or safe-write
  policies;
- customer facts, private logs, credentials or `.env` values;
- unresolved public-safety questions.

## Handoff Flow

```text
workshop project
-> public-safety pass
-> channel/canonical decision
-> final public text or metadata-only record
-> update data/publications.yml
-> update data/publication-venues.yml when needed
-> update public page or series page
-> run publication graph validation
```

## Metadata-Only Mode

Use metadata-only mode when the article is:

- still under moderation;
- published on a platform where full-text mirroring is not yet cleared;
- better represented by a canonical external URL;
- not safe to mirror as full text.

In metadata-only mode, record status, venue, URL when available, summary and
canonical decision. Do not include private drafts or exact submission copies.

## Validation

Before committing structure, metadata, full-text mirrors or boundary changes,
run:

```bash
npm run validate:graph
```
