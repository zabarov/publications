# SitePack v0.4.0 Release Note Draft

Status: draft for release-scope review.

This note should be published only after the current SitePack repository diff,
dependency audit findings and PHP tooling notes are reviewed.

## Summary

SitePack v0.4.0 moves the project from a basic package format toward a more
usable website portability standard.

This draft release focuses on:

- package conformance levels;
- profile contracts;
- extension governance;
- site structure support;
- object-oriented package inspection;
- stronger Node.js and PHP validation paths.

## Why This Release Matters

The release makes SitePack easier to discuss as a standard.

Earlier package work answered the basic question: how do we put website data
into a predictable package?

This release starts answering the next question: what does it mean for a tool to
support SitePack?

The answer is split into roles:

- Reader;
- Validator;
- Archive tool;
- Previewer;
- Importer Basic;
- Importer Advanced;
- Extension Importer;
- Exporter Core;
- Exporter Full.

That vocabulary makes implementation claims clearer. A tool can validate
packages without claiming full import support. A previewer can render a package
for human inspection without claiming production import readiness.

## Main Additions

### Conformance Levels

`sitepack-spec/CONFORMANCE.md` defines support levels for tools.

This gives implementers a practical ladder:

1. read the package;
2. validate structure and known media types;
3. preserve the package as an archive;
4. preview the package;
5. import portable data;
6. resolve relations and profile obligations;
7. support declared extensions;
8. export valid packages.

### Profile Contracts

Profiles describe package intent without naming a CMS.

Current profile contracts include:

- `config-only`;
- `content-only`;
- `site-structure`;
- `content-assets`;
- `site-snapshot`;
- `product-package`.

Each profile defines expected artifacts and importer/exporter obligations.

### Extension Governance

Extensions carry platform-specific data without changing SitePack Core.

The extension model lets adapters declare data such as Larena, Bitrix,
WordPress or custom platform artifacts while keeping portable content in core
media types.

Importers should preserve, report, skip or explicitly handle extension artifacts
instead of silently applying data they do not understand.

### Site Structure

The release adds `site-map` support for portable website structure:

- site identity;
- locales;
- routes;
- pages;
- menus;
- redirects;
- portable page references.

This supports package previews, site snapshots and migration planning.

### Object Layer

The Objects layer gives a human-friendly index over package artifacts.

Object indexes and object passports make it easier to inspect what a package
contains before importing it.

### Reference Validation

The Node.js and PHP validators are aligned with the updated schemas and
examples.

The validation path checks examples across the specification and reference
tools.

## Validation

Current passing validation command:

```bash
PATH=/Applications/ServBay/bin:/Users/rim/.cache/codex-runtimes/codex-primary-runtime/dependencies/node/bin:$PATH make validate-all
```

Observed results:

- schema sync passes;
- Node example validation passes;
- PHP example validation passes.

## Known Release-Cleanup Items

Before final publication:

- review current tracked and untracked local changes;
- decide exact release scope;
- handle or document Node dependency audit findings;
- reduce PHP 8.4 Composer deprecation noise or document the recommended PHP
  environment;
- confirm that public README examples match the final command set.

## Public Boundary

This release is about the SitePack open standard and reference validation
tools.

It does not publish:

- `bx-simai.sitepack`;
- real customer packages;
- private migration archives;
- credentials;
- applied adapter implementation details.

## Suggested Announcement

SitePack v0.4.0 introduces conformance levels, profile contracts, extension
governance and site-structure support for a portable website package format.

The release gives implementers a clearer path: read, validate, archive, preview,
import or export SitePack packages with explicit capability boundaries.
