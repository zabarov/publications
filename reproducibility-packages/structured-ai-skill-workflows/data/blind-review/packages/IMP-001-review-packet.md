# PILOT-002 Blind Review Packet: IMP-001

## Task

Plan implementation for notifications with missing delivery constraints.

Known evaluation concerns: recipient scope, delivery timing, idempotency, retries, permission boundaries and auditability.

## Output A

Partial planning is safe; implementation with external delivery is blocked until constraints are explicit.

Scope: plan notifications where requirements are incomplete. Desired output: safe implementation sequence and blockers.

Evidence used: task description only. Evidence missing: event trigger, recipient source, permission model, channel/template policy, queue/retry behavior, duplicate-prevention rule, audit requirement and failure-handling expectation.

Risk review: duplicate or premature notifications; incorrect recipient targeting; delivery before source transaction is stable; retries without idempotency; missing audit trail for compliance or support analysis.

Recommendation: split work into two phases. Phase 1: define event, recipient resolver, permission check, idempotency key, audit record and tests. Phase 2: implement queue-backed delivery only after acceptance criteria are approved.

QA: this preserves progress while avoiding unsafe side effects. The plan does not assume a channel, recipient rule or retry policy.

Handoff: continue by collecting answers for trigger, recipients, channel, idempotency, retries and audit. Then convert them into acceptance tests.

## Output B

The notification implementation should start with clarification before coding. The missing constraints are delivery channel, recipient scope, timing, idempotency, retry behavior, audit requirements and opt-out or permission boundaries.

Evidence needed: product requirement, event source, recipient model, notification templates, queue/retry policy, duplicate-prevention rule and logging/audit expectation.

Next steps: write an implementation plan that defines the event trigger, recipient resolution, delivery service, idempotency key, retry limits, failure logging and tests.

Blocker: do not implement delivery side effects until recipient and duplicate-notification rules are clear.

## Output C

Implementation planning can proceed, but delivery code is blocked until missing constraints are resolved.

The task asks for notification implementation with incomplete requirements. Notifications create user-visible side effects and may duplicate messages if trigger and idempotency rules are weak.

Used: task facts about missing delivery constraints. Missing: trigger event, recipient rules, channel, template source, retry policy, opt-out policy, audit requirements and idempotency strategy.

Risks: duplicate notifications; messages sent to wrong recipients; delivery before transaction commit; no audit trail for sent or failed messages; retry storms or silent failures.

Recommendation: design an event-to-delivery flow: trigger, recipient resolver, permission check, idempotency key, queue job, retry policy, audit log and tests. Hold implementation of side effects until recipient and duplicate rules are approved.

Review: the plan is actionable if the missing constraints are treated as acceptance criteria.

Continuation: next executor needs requirement answers for recipient scope, delivery timing, idempotency and audit logging.
