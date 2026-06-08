# PILOT-002 Controlled Protocol

Date: 2026-06-08

## Purpose

Test whether the role-separated workflow advantage observed in PILOT-001 survives basic prompt-control and scoring-independence safeguards.

## Status

Protocol executed as a non-independent controlled pilot.

Outputs and scoring exist, but the result is not independent validation.

## Research Question

When instruction detail and output sections are controlled more carefully, do role-separated AI skill workflows produce more reviewable outputs than monolithic or matched-section baselines on synthetic software-engineering workflow tasks?

## Conditions

| Condition | Label for execution | Reviewer label | Purpose |
| --- | --- | --- | --- |
| Compact monolithic | `C0_COMPACT` | hidden | Preserve continuity with PILOT-001 baseline |
| Matched-section baseline | `C1_SECTIONED` | hidden | Control for sectioned output without explicit role/gate model |
| Role-separated workflow | `C2_ROLE_SEPARATED` | hidden | Test the governed responsibility/gate workflow |

Primary comparison: `C1_SECTIONED` vs `C2_ROLE_SEPARATED`.

Secondary comparison: `C0_COMPACT` vs `C2_ROLE_SEPARATED`.

## Prompt-Control Rule

- `C1_SECTIONED` and `C2_ROLE_SEPARATED` must be approximately matched by word count and section count.
- Target range: within +/- 10 percent by word count.
- `C0_COMPACT` is retained as a practical compact baseline and should not be used as the main causal comparison.

## Task Set

Use the five public-safe synthetic tasks from benchmark package v0:

1. `RSK-001`: authentication fallback risk review.
2. `IMP-001`: notification implementation planning with missing constraints.
3. `BUG-001`: failing build triage with environment uncertainty.
4. `HND-001`: continuation handoff after partial repository task.
5. `PUB-001`: manuscript claim review for premature scientific claims.

Do not add new tasks inside PILOT-002 unless the protocol is versioned to `PILOT-002b`.

## Output Generation

For each task:

1. Generate one output for each condition.
2. Store raw outputs under `data/outputs/raw/`.
3. Create blinded copies under `data/blind-review/packages/`.
4. Preserve the condition mapping separately from reviewer-facing packets.

## Blinding

- Reviewer files must use neutral labels: `A`, `B`, `C`.
- The condition key must not be included in reviewer packets.
- Scoring sheets must list task ID and blind label only.

## Scoring

Use the existing eight 0-2 metrics:

- blocker recall;
- blocker precision;
- evidence traceability;
- actionability;
- risk visibility;
- handoff completeness;
- boundary discipline;
- positive claim-first writing.

Add one optional notes column for reviewer rationale.

## Analysis

Report:

- total score per condition;
- mean per task;
- per-metric score distribution;
- deltas between `C1_SECTIONED` and `C2_ROLE_SEPARATED`;
- qualitative error patterns;
- reviewer uncertainty notes.

## Interpretation Rules

Allowed if role-separated scores higher:

- "PILOT-002 provides controlled pilot evidence that role-separated workflow structure may improve reviewability under this task set."

Blocked:

- "Role-separated workflows are generally superior."
- "The model is validated."
- "The model is ready as a standard."

If scores converge:

- Reframe the model as an operational design pattern whose value may come from sectioning, gates and review prompts rather than role separation alone.

If matched-section baseline scores higher:

- Revise the manuscript to separate useful process structure from unnecessary role language.

## Completion Criteria

PILOT-002 is complete as a non-independent controlled pilot because:

- prompts were word-count checked;
- blinded package exists;
- one non-independent scoring pass exists;
- condition key is preserved separately;
- result analysis states limits and publication-use decision.

PILOT-002 is not complete as independent validation. Independent validation requires a separate scoring pass with the condition key withheld until scores are recorded.
