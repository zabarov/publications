# Data Availability Statement v1

Date: 2026-06-08

## Recommended Manuscript Text

The current study uses public-safe synthetic benchmark tasks, generated outputs and metadata-level abstractions of AI skill workflows. The working project stores the benchmark protocol, prompts, blind-review packets, scoring files and aggregate pilot results under the project workspace. Private skill internals, raw operational playbooks and sensitive repository-specific instructions are excluded from public artifacts. The reproducibility package candidate includes the synthetic tasks, prompt conditions, reviewer instructions, scoring rubric, scoring sheet and aggregate analysis, with private-source material removed.

## Package Evidence Paths

- Synthetic tasks: `data/tasks/`
- PILOT-002 prompts: `data/prompts/`
- PILOT-002 raw outputs: `data/outputs/raw/`
- PILOT-002 blind packets: `data/blind-review/packages/`
- PILOT-002 scoring: `data/scores/PILOT-002-scoring.csv`
- PILOT-002 decoded scoring: `data/scores/PILOT-002-decoded-scoring.csv`
- PILOT-002 aggregate: `analysis/PILOT-002-aggregate.csv`
- PILOT-002 result: `analysis/PILOT-002-result.md`

## Public Package Boundary

Allowed in public package:

- synthetic tasks;
- prompt templates;
- reviewer instructions;
- scoring rubric;
- scoring sheets;
- aggregate results;
- manuscript tables derived from the public-safe benchmark.

Excluded from public package:

- raw private skill sources;
- private operational rules;
- access data, runtime logs or sensitive internal implementation details;
- unpublished private corpus content unless explicitly sanitized and approved.

## Gate

Before public release, run a source-boundary and secret scan on the package. The package is not yet public-release-ready.
