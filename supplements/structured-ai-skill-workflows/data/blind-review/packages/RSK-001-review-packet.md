# PILOT-002 Blind Review Packet: RSK-001

## Task

Review a patch that changes authentication fallback behavior.

Known evaluation concerns: authentication bypass, fallback scope, audit visibility, regression coverage and rollback safety.

## Output A

The patch is not ready for merge until fallback scope, tests and audit behavior are verified.

The task concerns an authentication fallback change. The main concern is whether the fallback path changes the security boundary or becomes available outside the intended failure case.

Used: task facts about authentication fallback behavior. Missing: diff, existing auth flow, fallback trigger conditions, feature flag rules, test results, audit logs and rollback plan.

Risks: primary authentication bypass; fallback enabled in production unintentionally; missing audit trail for fallback use; no regression test for disabled fallback; unclear rollback if the change behaves incorrectly.

Recommendation: do not merge yet. Require explicit fallback trigger rules, tests for primary/fallback/disabled cases, audit logging and rollback instructions.

Review: the recommended stop is proportional to the security risk. The answer should be updated after the exact diff and tests are available.

Continuation: next reviewer needs the patch diff, auth flow documentation, test output and deployment guard details.

## Output B

Blocked for merge. The change touches authentication behavior and needs evidence before approval.

Scope: review a patch that adds or changes authentication fallback behavior. Desired output: merge decision, risks, evidence required and continuation state.

Evidence used: task description only. Evidence missing: exact diff, current authentication decision tree, fallback activation guard, environment/feature-flag policy, audit/logging behavior, automated tests and rollback procedure.

Risk review: fallback may bypass primary authentication; fallback may activate outside a narrow outage/recovery condition; missing logs may hide use of the fallback path; tests may cover happy path while missing disabled and failure cases; deployment without a kill switch could make rollback slow.

Recommendation: return `block/review` rather than merge. Require scoped activation rules, regression tests for primary/fallback/disabled paths, audit logging for each fallback use and a rollback or kill-switch plan.

QA: the decision is evidence-bound. No completion claim is made because the diff and tests are missing.

Handoff: continue by collecting the diff, auth-flow map, test output, logging plan and deployment guard. Re-score after evidence is attached.

## Output C

The authentication fallback patch should not be accepted as complete without more evidence. The key risks are silent bypass of the primary authentication path, inconsistent behavior across environments, missing audit visibility and possible expansion of fallback use beyond the intended failure case.

Evidence needed: the exact diff, current authentication flow, feature flag or environment guard, tests for primary and fallback paths, audit/logging behavior and rollback plan.

Next steps: block merge until fallback scope is explicit, add tests for success/failure/disabled fallback cases, require audit logging for fallback use and document an operator-visible rollback or kill switch.

Blocker: if the fallback can authenticate a user when the primary path is unavailable without a narrow guard, the change is unsafe.
