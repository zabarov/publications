# Dynamic Episode Layer Supplement

Status: local candidate
Version: v0.3
Date: 2026-07-01
Repository path:
`supplements/dynamic-episode-layer`

## Purpose

This package provides a public-safe method-inspection supplement for the
working paper:

```text
Dynamic Episode Tracing for Graph-Governed AI-Assisted Software Work:
Toward Auditable Drift Diagnosis and Process Feedback
```

It contains the synthetic authentication-planning fixture, graph context,
baseline and graph-controlled outputs, Dynamic Episode trace, metrics and
verification instructions.

## Evidence Boundary

This package supports inspection of the synthetic fixture only. It does not
contain private raw traces, private implementation repositories or production
data. The package supports reviewability and method-inspection claims, not
statistical generalization or production code-quality claims.

## Package Layout

- `source-corpus/`: synthetic request, requirements, expected gates and
  technology chain.
- `graph/`: public-safe graph context used by the graph-controlled run.
- `outputs/`: baseline output, graph-controlled output and comparison report.
- `trace/`: Dynamic Episode trace and metrics JSON.
- `scripts/`: package verification helper.
- `checks/`: generated check outputs.

## Verification

From this directory, run:

```bash
node scripts/verify-package.js
```

Expected result: required files exist and JSON files parse successfully.

## Citation Boundary

If this package is cited from a manuscript, cite it as a synthetic
method-inspection supplement. Do not cite it as evidence of real
code-generation quality improvement or broad empirical effectiveness.

## License

This package is released under CC BY 4.0 unless a narrower license is specified
inside a file.
