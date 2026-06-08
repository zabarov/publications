# BUG-001 / C2_ROLE_SEPARATED

## Status

Triage-only. Code changes are blocked until reproduction evidence exists.

## Intake

Scope: analyze a failing build where runtime information is incomplete. Desired output: evidence plan, risk boundary and next action.

## Evidence

Evidence used: task description only. Evidence missing: exact command, full error log, dependency lock status, runtime versions, clean install result, CI status and recent change list.

## Risk Review

- false diagnosis from stale dependencies or cache;
- local-only runtime mismatch;
- code patch created without reproducing the failure;
- loss of evidence if logs are overwritten;
- unclear owner if failure is tooling rather than application code.

## Recommendation

Preserve the failing log, capture versions, run clean dependency install from lockfile, reproduce the smallest failing target and compare with CI. Only after that classify the failure as environment, dependency or code.

## QA

The answer avoids a premature fix claim and gives an ordered evidence path.

## Handoff

Continue with a triage note containing command, log excerpt, versions, lockfile status, CI comparison and first reproducible failing target.
