# Comparison Report: Dynamic Episode Auth Fixture

Date: 2026-06-16
Status: public_safe_synthetic_result

## Summary

The same synthetic authentication planning request was compared in two forms:

- baseline planning output;
- graph-controlled planning output with Dynamic Episode trace.

The graph-controlled output produced a more reviewable planning episode. It
recorded activated context, selected path, blocked alternatives, gate outcomes,
feedback classifications and canonical-update boundary.

## Main Differences

| Dimension | Baseline | Graph-controlled |
| --- | --- | --- |
| Activated graph context | Not recorded | 9 objects recorded |
| Blocked alternatives | Not recorded | 5 blocked paths recorded |
| Gate outcomes | Not recorded | 5 gate outcomes recorded |
| Unsupported assumptions | 5 | 1 |
| Boundary violations | 2 | 0 |
| Feedback routes | 0 | 4 |

## Interpretation

Supported claim:

The Dynamic Episode Layer can make a graph-controlled planning episode more
reviewable in a public-safe synthetic fixture.

Still unsupported:

- real code-generation drift reduction;
- statistical effect;
- production correctness;
- generalization beyond this fixture.

## Use In Article

This result can support a Methods/Design-Science subsection and a small
illustrative Results subsection. It should be described as a synthetic fixture
benchmark, not as real implementation evidence.
