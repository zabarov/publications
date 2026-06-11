# AI Work Field Five-Task Public Method-Inspection Package

Status: package plan

This package is intended to make the five-task AI Work Field pilot inspectable
and reproducible without exposing private operational materials.

## Purpose

The package should allow a reader to check:

- what the five pilot tasks were;
- which required objects and relations were expected;
- how the baseline and graph-field conditions were scored;
- how context reduction was calculated;
- which claims are supported by the pilot;
- which claims remain outside the evidence boundary.

## Planned Public Contents

```text
README.md
fixture-schema.json
execution-record-schema.json
scoring-rubric.md
protocol.md
fixtures/
executions/
results/controlled-measurement-summary.json
scripts/summarize-controlled-measurement.js
MANIFEST.json
LICENSE
```

## Public-Safety Boundary

The public package should contain public-safe fixtures and execution records
only. Private project names, local paths, private repository internals,
credentials, client data, unpublished business material and raw operational logs
must stay outside the public package.

## Reproducibility Claim

Allowed:

```text
The package enables method inspection and independent replication of the summary
calculation.
```

Not allowed:

```text
The package proves independent validation.
```

## Minimum Package Gate

Before publication, verify:

- schemas validate all fixtures and execution records;
- summary script reproduces the reported 54.84% mean context reduction;
- all five fixtures and five execution records are present;
- no private paths or private repository identifiers remain;
- public link is live before the manuscript cites it as available.

## Recalculate Summary

From this package root:

```bash
node scripts/summarize-controlled-measurement.js
```

Expected result:

- `fixture_count`: `5`
- `execution_record_count`: `5`
- `mean_context_reduction_percent`: `54.84`
- `graph_pilot_pass_count`: `5`
- `baseline_pilot_fail_count`: `5`
- `reviewer_modes`: `author_non_independent_review`
