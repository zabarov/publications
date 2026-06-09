# RSK-001 / C2_ROLE_SEPARATED

## Status

Blocked for merge. The change touches authentication behavior and needs evidence before approval.

## Intake

Scope: review a patch that adds or changes authentication fallback behavior. Desired output: merge decision, risks, evidence required and continuation state.

## Evidence

Evidence used: task description only. Evidence missing: exact diff, current authentication decision tree, fallback activation guard, environment/feature-flag policy, audit/logging behavior, automated tests and rollback procedure.

## Risk Review

- fallback may bypass primary authentication;
- fallback may activate outside a narrow outage/recovery condition;
- missing logs may hide use of the fallback path;
- tests may cover happy path while missing disabled and failure cases;
- deployment without a kill switch could make rollback slow.

## Recommendation

Return `block/review` rather than merge. Require scoped activation rules, regression tests for primary/fallback/disabled paths, audit logging for each fallback use and a rollback or kill-switch plan.

## QA

The decision is evidence-bound. No completion claim is made because the diff and tests are missing.

## Handoff

Continue by collecting the diff, auth-flow map, test output, logging plan and deployment guard. Re-score after evidence is attached.
