# SitePack Release-Readiness Audit

Audit date: 2026-06-12

Repository: <https://github.com/simai/sitepack>

## Verdict

SitePack is technically close to a public release package, but it should not be
announced as release-ready yet.

The core standard and validators are working. The remaining work is release
cleanup: review local changes, decide what belongs in the release, handle
dependency audit findings and reduce local environment noise.

## What Already Works

- The repository has a clear public purpose.
- The project is platform-neutral.
- The repository includes a specification, schemas, registries, examples,
  conformance material and reference validators.
- Schema sync passes across the specification, Node validator and PHP validator.
- Node example validation passes.
- PHP example validation passes when the environment uses ServBay PHP/Composer.

## Validation Evidence

Passing command:

```bash
PATH=/Applications/ServBay/bin:/Users/rim/.cache/codex-runtimes/codex-primary-runtime/dependencies/node/bin:$PATH make validate-all
```

Observed result:

- schema directories are synchronized;
- Node validator validates examples successfully;
- PHP validator validates examples successfully.

## Findings

### Needs Review Before Announcement

The local SitePack repository contains tracked and untracked changes related to
the next standard version:

- specification updates;
- conformance material;
- profile and extension documentation;
- `small-docs-site` example;
- `site-map` schema;
- Node validator updates;
- PHP validator updates.

These changes may be valid, but they need a focused review before any public
release announcement.

### Dependency Audit

`npm install` reports four dependency vulnerabilities in the Node validator
tooling:

- 2 moderate;
- 2 high.

This does not mean the SitePack format is broken, but it should be handled
before a polished release announcement.

### PHP Environment Noise

The first PHP validation attempt failed because the shell used a broken
Homebrew PHP/ICU setup. With ServBay PHP/Composer, validation passes.

Composer on PHP 8.4 emits deprecation notices. This is environment/tooling
noise, but it should be cleaned up or documented before release.

## Public Boundary

Public:

- standard;
- schemas;
- examples;
- registries;
- conformance material;
- Node/PHP reference validators.

Out of scope:

- `bx-simai.sitepack`;
- real customer packages;
- migration archives;
- credentials;
- private adapter implementation details.

## Next Goal

Prepare a release-ready SitePack package and introductory article.

Done means:

- the current local diff is reviewed;
- the release scope is fixed;
- dependency audit findings are handled or documented;
- PHP validation has a clean recommended command;
- examples and validators pass;
- the public README and release note explain one clear adoption path.

This goal connects to the final result because SitePack should be introduced as
a credible open standard with working validation, not as an unfinished internal
experiment.
