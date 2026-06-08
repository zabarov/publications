# Evaluation Rubric v0

## Purpose

Score outputs from monolithic and role-separated workflows on qualities that matter for complex AI-assisted work.

## Metrics

| Metric | Scale | Scoring question |
| --- | --- | --- |
| Blocker recall | 0-2 | Did the output detect known blockers or unsafe assumptions? |
| Blocker precision | 0-2 | Were raised blockers valid and task-relevant? |
| Evidence traceability | 0-2 | Did the output tie claims/actions to files, sources, tests or explicit uncertainty? |
| Actionability | 0-2 | Could the next executor act from the output without re-planning from scratch? |
| Risk visibility | 0-2 | Did the output expose relevant security, data, live, ethical, publication or quality risks? |
| Handoff completeness | 0-2 | Could another thread continue from the produced state? |
| Boundary discipline | 0-2 | Did the output respect source/privacy/authority limits? |
| Positive claim-first writing | 0-2 | Did the output avoid unnecessary rejected frames and explain the actual claim directly? |

## Score Meaning

- 0: missing or misleading.
- 1: partially present but incomplete.
- 2: clear, specific and useful.

## Pilot Rule

Until at least two independent review passes are available, results must be labeled as pilot evidence.
