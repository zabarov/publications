# SitePack

SitePack is an open standard for packaging website data so a site can be
exported, validated, archived, previewed and imported between systems.

Repository: <https://github.com/simai/sitepack>

## Current Result

The project has a public-oriented repository with:

- a platform-neutral specification;
- JSON schemas and registries;
- example packages;
- conformance materials;
- profile contracts and extension governance;
- adapter-proof examples;
- a Node.js reference validator;
- a PHP reference validator;
- release-candidate notes for `0.5.0-rc.1`.

In simple terms, SitePack defines what a portable website package should look
like. Instead of treating every migration as a custom script, it creates a
shared format that different systems can read, check and exchange.

## What It Gives

SitePack gives a common language for website portability:

- a website can be packaged as a document-like artifact;
- validators can check whether the package is structurally correct;
- platforms can build exporters and importers around the same core format;
- Bitrix, Larena, WordPress, Drupal and custom systems can stay adapters rather
  than becoming the center of the standard.

This makes the idea useful for backups, migrations, previews, audits and
long-term archival work.

## Public Boundary

Public:

- the standard;
- schemas;
- examples;
- registries;
- reference validators;
- release notes and public documentation.

Private or out of scope:

- customer packages;
- real migration archives;
- credentials or server data;
- applied Bitrix implementation details;
- `bx-simai.sitepack`.

## Next Goal

The current public goal is a release-candidate announcement or article based on
SitePack `0.5.0-rc.1`.

Done means:

- the publication channel is selected;
- the release-candidate material is adapted to that channel;
- the text links to the public repository and release-candidate notes;
- the article or release note does not claim a final tagged release before one
  exists.

This goal connects directly to the final result: SitePack should become a
credible public standard, not just a repository with useful files.

## Release-Readiness Audit

See the current audit:

- [SitePack release-readiness audit](release-readiness/)

## Publication Kit

The first public article draft and release-candidate release note are prepared
here:

- [SitePack publication kit](publication-kit/)
