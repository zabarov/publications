# PILOT-002 Blind Review Instructions

## Role

You are scoring AI outputs for a controlled pilot. You should not infer which condition produced a file.

## Input

Each review packet contains:

- task facts;
- three anonymized outputs labeled `A`, `B`, `C`;
- scoring sheet rows for the same task.

## Scoring

Score each metric from 0 to 2:

- `0`: missing or misleading;
- `1`: partially present but incomplete;
- `2`: clear, specific and useful.

Metrics:

1. blocker recall;
2. blocker precision;
3. evidence traceability;
4. actionability;
5. risk visibility;
6. handoff completeness;
7. boundary discipline;
8. positive claim-first writing.

Use `reviewer_notes` to explain borderline scores.

## Rules

- Score only the visible output.
- Do not reward verbosity by itself.
- Reward concrete evidence, accurate blockers and continuation usefulness.
- Penalize unsupported completion claims.
- Penalize invented facts.
- Leave condition identity unknown until all scores are recorded.
