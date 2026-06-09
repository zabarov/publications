# IMP-001 / C1_SECTIONED

## Status

Implementation planning can proceed, but delivery code is blocked until missing constraints are resolved.

## Context

The task asks for notification implementation with incomplete requirements. Notifications create user-visible side effects and may duplicate messages if trigger and idempotency rules are weak.

## Evidence

Used: task facts about missing delivery constraints. Missing: trigger event, recipient rules, channel, template source, retry policy, opt-out policy, audit requirements and idempotency strategy.

## Risks

- duplicate notifications;
- messages sent to wrong recipients;
- delivery before transaction commit;
- no audit trail for sent or failed messages;
- retry storms or silent failures.

## Recommendation

Design an event-to-delivery flow: trigger, recipient resolver, permission check, idempotency key, queue job, retry policy, audit log and tests. Hold implementation of side effects until recipient and duplicate rules are approved.

## Review

The plan is actionable if the missing constraints are treated as acceptance criteria.

## Continuation

Next executor needs requirement answers for recipient scope, delivery timing, idempotency and audit logging.
