# IMP-001 / C2_ROLE_SEPARATED

## Status

Partial planning is safe; implementation with external delivery is blocked until constraints are explicit.

## Intake

Scope: plan notifications where requirements are incomplete. Desired output: safe implementation sequence and blockers.

## Evidence

Evidence used: task description only. Evidence missing: event trigger, recipient source, permission model, channel/template policy, queue/retry behavior, duplicate-prevention rule, audit requirement and failure-handling expectation.

## Risk Review

- duplicate or premature notifications;
- incorrect recipient targeting;
- delivery before source transaction is stable;
- retries without idempotency;
- missing audit trail for compliance or support analysis.

## Recommendation

Split work into two phases. Phase 1: define event, recipient resolver, permission check, idempotency key, audit record and tests. Phase 2: implement queue-backed delivery only after acceptance criteria are approved.

## QA

This preserves progress while avoiding unsafe side effects. The plan does not assume a channel, recipient rule or retry policy.

## Handoff

Continue by collecting answers for trigger, recipients, channel, idempotency, retries and audit. Then convert them into acceptance tests.
