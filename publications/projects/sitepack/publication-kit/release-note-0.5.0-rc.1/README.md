# SitePack 0.5.0-rc.1 Release Note

Status: ready public material for release-candidate announcement.

SitePack `0.5.0-rc.1` packages the current standard work into a reviewable
public release candidate. It is not a final tagged release yet. It is the
material that explains what has become ready for developer review and what still
belongs to the final release step.

## Summary

SitePack now has the core pieces needed to discuss website portability as a
standard:

- conformance levels;
- portable profile contracts;
- extension governance;
- `site-map` and `site-structure`;
- adapter-proof examples;
- Node.js and PHP reference validation;
- release-candidate notes in the SitePack repository.

The practical point is simple: a website export should be inspectable before an
importer starts changing a real system.

## Why This Matters

Website migration is often implemented as a one-off script. That works for a
specific source, target and moment in time, but it makes the exported data hard
to inspect independently.

SitePack adds a portable package layer between export and import.

With this layer, a team can:

- export website data into a predictable package;
- validate the package structure;
- inspect content, assets, routes, pages, menus and redirects;
- separate portable data from platform-specific adapter data;
- decide what an importer can safely apply.

This creates a better boundary for backups, migrations, previews and long-term
archives.

## What Changed In The Candidate

### Conformance Levels

SitePack now has named support levels for tools:

- Reader;
- Validator;
- Archive;
- Previewer;
- Importer;
- Exporter.

This makes support claims more precise. A tool can validate SitePack packages
without claiming full import support. A previewer can render a package for
human inspection without claiming production import readiness.

### Profile Contracts

Profiles describe the intent of a package without naming a CMS.

Current profile contracts cover:

- configuration;
- content;
- site structure;
- content assets;
- site snapshots;
- product packages.

Profiles help developers understand what a package is expected to contain and
what a compatible tool should be able to do with it.

### Extension Governance

Platform-specific data belongs in extensions and adapters.

That keeps SitePack Core platform-neutral. Bitrix, Larena, WordPress, Drupal,
static-site generators and custom systems can be handled around the core format
without turning any one platform into the center of the standard.

### Site Structure

The candidate adds `site-map` and the `site-structure` profile.

This gives a portable way to describe:

- site identity;
- locales;
- routes;
- pages;
- menus;
- redirects.

This is useful for previews, migration planning and adapter testing.

### Adapter Proof

The candidate includes two public examples:

- `small-docs-site`;
- `small-blog-site`.

They show that the same portable SitePack structures can describe different
website shapes without embedding CMS-specific behavior into the core format.

## Validation

The release candidate is backed by passing repository checks.

Validated in the SitePack repository:

```bash
PATH=/Applications/ServBay/bin:/Users/rim/.cache/codex-runtimes/codex-primary-runtime/dependencies/node/bin:$PATH make validate-all
cd sitepack-tools-node && npm audit --json
git diff --check
```

Observed result:

- schema synchronization passes;
- Node example validation passes;
- PHP example validation passes;
- Node audit reports zero vulnerabilities;
- Docara documentation build passes with ServBay PHP;
- GitHub Actions Pages workflow passes.

## Public Boundary

This release note is about the public SitePack standard and reference tools.

It does not publish:

- `bx-simai.sitepack`;
- customer packages;
- real migration archives;
- credentials;
- private adapter implementation details;
- a final Git tag or GitHub Release.

## Suggested Announcement

SitePack `0.5.0-rc.1` is ready for developer review.

The candidate adds conformance levels, portable profile contracts, extension
governance, `site-structure` examples and reference validation for Node.js and
PHP. It makes SitePack easier to evaluate as a standard for portable website
packages: what a tool supports, what a package contains and where
platform-specific adapter data belongs.

## Next Step

The next release step is to decide the final version boundary, update versioned
schemas/examples/tools if needed, create a tag or GitHub Release, and then point
public articles to that final release.
