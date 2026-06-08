# IMP-001 / C0_COMPACT

The notification implementation should start with clarification before coding. The missing constraints are delivery channel, recipient scope, timing, idempotency, retry behavior, audit requirements and opt-out or permission boundaries.

Evidence needed: product requirement, event source, recipient model, notification templates, queue/retry policy, duplicate-prevention rule and logging/audit expectation.

Next steps: write an implementation plan that defines the event trigger, recipient resolution, delivery service, idempotency key, retry limits, failure logging and tests.

Blocker: do not implement delivery side effects until recipient and duplicate-notification rules are clear.
