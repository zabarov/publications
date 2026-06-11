# AI Work Field Controlled Measurement Scoring Rubric

Date: 2026-06-11

Status: rubric candidate

## Purpose

This rubric makes pilot scoring explicit before data collection. It prevents
the study from changing evaluation rules after seeing results.

## Scoring Dimensions

### 1. Context Efficiency

Metric:

- `context_reduction_percent`

Interpretation:

- positive value: graph-field condition used fewer normalized context units;
- zero or negative value: graph-field condition did not reduce context size.

Boundary:

- this metric measures context size only; it does not measure correctness.

### 2. Context Preservation

Metrics:

- `required_object_recall`;
- `required_relation_recall`;
- `critical_omission_count`.

Scores:

| Score | Rule |
| --- | --- |
| `pass` | all required objects and relations present; zero critical omissions |
| `partial` | minor non-critical omission; task decision still valid |
| `fail` | one or more critical omissions change the task decision |

### 3. Transition Control

Metric:

- `invalid_transition_detected`

Scores:

| Score | Rule |
| --- | --- |
| `pass` | invalid transition blocked or valid transition accepted with evidence |
| `partial` | transition warning present but incomplete evidence code |
| `fail` | invalid transition accepted or valid transition rejected without reason |
| `not_applicable` | task does not involve a workflow transition |

### 4. Evidence Boundary

Metric:

- `unsupported_claim_count`

Scores:

| Score | Rule |
| --- | --- |
| `pass` | no unsupported claims |
| `partial` | unsupported claim identified and corrected before final output |
| `fail` | unsupported claim remains in scored output |

### 5. Handoff Continuity

Metric:

- `handoff_state_match`

Scores:

| Score | Rule |
| --- | --- |
| `pass` | current state, blocker and next action are reconstructed |
| `partial` | state is reconstructed but blocker or next action is incomplete |
| `fail` | state or next action is materially wrong |
| `not_applicable` | task has no continuation component |

### 6. Reviewer Correction Load

Metric:

- `review_correction_count`

Interpretation:

- lower is better only when context preservation and evidence boundary pass;
- a low correction count with missed critical dependencies is not a success.

## Overall Pilot Verdict

| Verdict | Rule |
| --- | --- |
| `measurement-ready` | schema, rubric and scoring fields are complete |
| `pilot-pass` | context preservation, transition control and evidence boundary pass |
| `pilot-partial` | no critical failure, but at least one partial score |
| `pilot-fail` | critical omission, invalid transition acceptance or unsupported claim remains |

## Reporting Rule

Report pilot outputs as controlled pilot evidence. Do not describe the pilot as
proof of general effectiveness, production quality or independent validation.

