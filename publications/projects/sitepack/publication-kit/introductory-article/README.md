# SitePack: A Portable Package Format for Websites

Websites are moved all the time. A company changes CMS. A project moves from an
old platform to a new one. A team needs a backup that a future system can still
understand. An integrator needs to copy content, assets, configuration and page
structure from one environment to another.

The usual answer is a migration script. It works once, for one source, one
target and one moment in time. After that, the knowledge often stays inside the
script, inside a developer's head, or inside a project folder that nobody wants
to touch again.

SitePack takes another route. It treats a website export as a portable package
with a predictable structure, a manifest, a catalog, typed artifacts and
validation rules. The goal is simple: make website data movable and inspectable
before any importer starts changing a real system.

## The Problem

Website migration is usually discussed as an implementation task:

- write an exporter;
- parse some source data;
- transform content;
- copy files;
- rebuild relations;
- import everything into the target CMS.

That work is necessary, but it misses a layer. Before import, we need to know
what exactly was exported. We need to check whether files are present, whether
assets match their indexes, whether relations point somewhere meaningful, and
whether the package contains portable data or platform-specific data.

Without that layer, every migration becomes a custom conversation between two
systems. The export format is hidden inside code. The import result is hard to
inspect. A failed migration tells us that something broke, but not always where
the package stopped being understandable.

## What SitePack Is

SitePack is an open format for packaging website data for transfer between
systems.

A SitePack package has two required files:

- `sitepack.manifest.json`;
- `sitepack.catalog.json`.

The manifest describes the package: format name, version, package id, creation
time, declared profiles and artifact ids.

The catalog lists the files inside the package: artifact id, media type, path,
size and optional digest.

Everything else is an artifact. An artifact can describe content entities,
assets, configuration records, site structure, object passports, recordsets or
extension data. The importer reads the manifest and catalog as the source of
truth instead of guessing meaning from file extensions or folder names.

## What Makes It Useful

The first useful property is validation.

A package can be checked before import:

```bash
node bin/sitepack-validate <packageRoot>
```

or with the PHP reference validator:

```bash
./bin/sitepack-validate package <packageRoot>
```

That check gives a practical boundary. A package can be valid as a SitePack
package even if a specific target system still cannot import every artifact.
That distinction matters. It separates package correctness from adapter
capability.

The second useful property is portability.

SitePack Core is platform-neutral. Bitrix, Larena, WordPress, Drupal, static
site generators and custom systems belong around the core as exporters,
importers or extensions. The core format should describe portable website data;
platform-specific behavior should be declared as extension data.

The third useful property is inspection.

A SitePack package can be archived, previewed, validated or imported at
different conformance levels. A tool can be a Reader, Validator, Archive tool,
Previewer, Importer or Exporter. This makes support claims more precise than
"supports SitePack".

## Profiles

SitePack uses profiles to describe package intent.

Current profile contracts include:

- `config-only` for settings and options;
- `content-only` for content entities without required binary assets;
- `site-structure` for site identity, locales, routes, pages, menus and
  redirects;
- `content-assets` for content plus binary assets;
- `site-snapshot` for archival or preview-oriented packages;
- `product-package` for installable products, solutions, themes, plugins or
  starter-site metadata.

Profiles are not CMS names. A CMS adapter can support a profile, but the profile
itself should stay portable.

## Extensions

Some data is inevitably platform-specific. SitePack handles that through
extensions.

An extension can carry data for a specific adapter without redefining the core
format. For example, a Larena or Bitrix adapter can declare its own extension
artifacts while keeping portable entities, assets and site structure in core
media types.

This keeps the standard from becoming a list of CMS-specific exceptions. It also
lets importers make a clear decision: apply the extension, preserve it, skip it
with a warning, or stop when a required extension cannot be handled safely.

## A Simple Mental Model

Think of SitePack as a transport document for a website.

The manifest says what the package claims to contain.

The catalog says where each artifact is and how to identify it.

Schemas and validators check whether the structure is understandable.

Profiles say what kind of package this is.

Extensions carry platform-specific details without polluting the portable core.

Importers and exporters then become adapters around a shared package language.

## Current Status

The current SitePack work includes:

- specification;
- schemas;
- registries;
- examples;
- conformance material;
- profile contracts;
- extension governance;
- Node.js reference validator;
- PHP reference validator.

The validation path is already working. A release-readiness audit showed that
schema sync, Node validation and PHP validation pass when the environment uses
the intended Node and PHP tooling.

There are still release-cleanup items before a polished announcement:

- review current local changes;
- settle the release scope;
- handle or document dependency audit findings;
- clean up PHP 8.4 tooling noise;
- make the public validation path easy to repeat.

## Why This Matters

A website package format will not remove the hard parts of migration. It will
not magically map every field, rewrite every template or solve every adapter
decision.

It gives teams a better boundary.

First, export a package.

Then validate the package.

Then inspect what it contains.

Then decide what an importer can apply safely.

That is a healthier workflow than treating migration as one large script that
touches the target system before the exported data has a stable shape.

## What Comes Next

The next step is to finish the release cleanup and publish a stable SitePack
release note. After that, the useful work moves to adapters: exporters,
importers, previews and reports for real platforms.

The core idea stays the same: a website should be movable as a package that
people and tools can inspect before anything is imported.
