# PILOT-002 Result

Date: 2026-06-08

## Status

Scoring completed as a non-independent scoring pass.

This is not independent validation. The same project context generated the outputs and performed the scoring. The result may be used for internal method development and manuscript planning, but not as independent empirical evidence.

## Inputs

- Protocol: `methodology/PILOT-002-controlled-protocol.md`
- Prompt control check: `methodology/PILOT-002-prompt-control-check.md`
- Raw outputs: `data/outputs/raw/`
- Blind packets: `data/blind-review/packages/`
- Condition mapping: retained in the private project workspace and omitted from this preview; decoded scoring is included for method inspection.
- Scoring file: `data/scores/PILOT-002-scoring.csv`
- Decoded scoring file: `data/scores/PILOT-002-decoded-scoring.csv`

## Design Reminder

PILOT-002 introduced a stronger control than PILOT-001:

- `C0_COMPACT`: compact monolithic continuity baseline.
- `C1_SECTIONED`: matched-section baseline without explicit role/gate framing.
- `C2_ROLE_SEPARATED`: governed role-separated workflow.

Primary comparison: `C1_SECTIONED` vs `C2_ROLE_SEPARATED`.

Prompt word counts:

| Condition | Words |
| --- | ---: |
| `C0_COMPACT` | 46 |
| `C1_SECTIONED` | 93 |
| `C2_ROLE_SEPARATED` | 100 |

`C1_SECTIONED` and `C2_ROLE_SEPARATED` are within the planned +/-10 percent word-count rule.

## Blind Label Decoding

| Task | A | B | C |
| --- | --- | --- | --- |
| `RSK-001` | `C1_SECTIONED` | `C2_ROLE_SEPARATED` | `C0_COMPACT` |
| `IMP-001` | `C2_ROLE_SEPARATED` | `C0_COMPACT` | `C1_SECTIONED` |
| `BUG-001` | `C0_COMPACT` | `C1_SECTIONED` | `C2_ROLE_SEPARATED` |
| `HND-001` | `C1_SECTIONED` | `C0_COMPACT` | `C2_ROLE_SEPARATED` |
| `PUB-001` | `C2_ROLE_SEPARATED` | `C1_SECTIONED` | `C0_COMPACT` |

## Aggregate Scores

| Condition | Total possible | Score | Mean per task |
| --- | ---: | ---: | ---: |
| `C0_COMPACT` | 80 | 69 | 13.8 |
| `C1_SECTIONED` | 80 | 78 | 15.6 |
| `C2_ROLE_SEPARATED` | 80 | 80 | 16.0 |

## Interpretation

The compact baseline scored lower than both structured conditions. The matched-section baseline nearly closed the gap with the role-separated condition. This suggests that a large part of the observed improvement may come from structured output sections, explicit evidence prompts and continuation prompts.

The role-separated condition still scored slightly higher in this non-independent pass. The difference between `C1_SECTIONED` and `C2_ROLE_SEPARATED` is small: `2` points out of `80`.

## Publication-Use Decision

Allowed:

- report PILOT-002 as a non-independent controlled pilot;
- state that matched-section controls reduced the apparent role-separated advantage;
- use the result to refine the research question and draft v2;
- use the result to justify independent scoring.

Blocked:

- claiming independent validation;
- claiming general superiority of role-separated workflows;
- claiming statistical significance;
- claiming submission-ready empirical evidence.

## Scientific Meaning

PILOT-002 is scientifically useful because it weakens an over-simple interpretation of PILOT-001. The new result suggests that the paper should be careful: role separation may matter, but sectioned workflow structure and explicit evidence/handoff prompts may explain much of the effect.

The next manuscript revision should shift from "role separation improves outputs" toward a more precise claim:

> Structured AI skill workflows that combine explicit sections, evidence prompts, gates and handoff requirements may improve reviewability. The incremental contribution of role labels over matched-section structure requires independent testing.

## Next Step

Either:

1. run an independent scoring pass over the existing blind packets; or
2. revise the manuscript into draft v2 using PILOT-002 as non-independent controlled pilot evidence.
