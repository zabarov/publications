# SitePack

SitePack is an open standard for packaging website data so a site can be
exported, validated, archived, previewed and imported between systems.

Repository: <https://github.com/simai/sitepack>

## Current Result

The project already has a public-oriented repository with:

- a platform-neutral specification;
- JSON schemas and registries;
- example packages;
- conformance materials;
- a Node.js reference validator;
- a PHP reference validator.

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

The next goal is a release-ready SitePack package.

Done means:

- the current local changes in the SitePack repository are reviewed;
- the specification and validators agree with each other;
- examples pass validation;
- the public README explains the standard in one clear path;
- a release note is ready;
- an introductory article can point to a stable public state.

This goal connects directly to the final result: SitePack should become a
credible public standard, not just a repository with useful files.

## Release-Readiness Audit

See the current audit:

- [SitePack release-readiness audit](release-readiness/)

## Publication Kit

The first public article draft and release-note draft are prepared here:

- [SitePack publication kit](publication-kit/)
