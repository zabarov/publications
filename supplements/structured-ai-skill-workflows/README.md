# Structured AI Skill Workflows Reproducibility Package

Status: public repository method-inspection package.

Date: 2026-06-08

## Purpose

This package is a public repository method-inspection package for the working paper:

`Structured AI Skill Workflows: A Design-Science Model for Evidence, Gates and Handoff in Software Engineering Assistance`

It contains public-safe synthetic benchmark materials for PILOT-002:

- synthetic tasks;
- prompt conditions;
- prompt-length control;
- blind-review packet structure;
- scoring rubric;
- non-independent scoring files;
- decoded aggregate result;
- data availability and declaration drafts.

## Disclosure Mode

Mode: `method_inspection_public`.

The package includes decoded scoring because PILOT-002 scoring has already been completed as non-independent scoring. It excludes the condition mapping file to preserve a cleaner boundary for any future independent-review package.

For a future independent-review package, exclude decoded scoring, aggregate results and any condition-key material until external scores are returned.

## Claim Boundary

Allowed:

- inspect the benchmark method;
- rerun synthetic tasks;
- inspect prompt conditions and scoring rubric;
- inspect the non-independent PILOT-002 result.

Not supported:

- independent validation claims;
- statistical significance claims;
- general superiority claims for role-separated workflows;
- journal-submission readiness claims;
- submission-ready package claims.

## Main Limitation

PILOT-002 scoring is non-independent. The same project context generated outputs and performed scoring. The result can support method development and preprint discussion, but it is not independent empirical validation.

## Directory Layout

```text
methodology/
data/
analysis/
manuscript/
source-boundary.md
data-dictionary.md
manifest.csv
CITATION.cff
LICENSE
```

## License

This package uses `CC BY 4.0`.

See `LICENSE`.

## Boundaries

- This package does not provide independent empirical validation.
- This package is not a journal-submission package.
- This package is not an SSRN attachment by itself.
- Future independent-review packages should exclude decoded scoring and aggregate results until external scores are returned.
