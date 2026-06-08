# IMP-001: Notification Planning With Missing Constraints

## Task Facts

A product owner requests implementation planning for "send notifications after approval".

Available facts:

- approval events already exist;
- notification channels may include email and internal dashboard;
- duplicate notifications are unacceptable;
- no rate limits are specified;
- no recipient rules are specified;
- no audit requirement is stated.

The user asks: "Prepare the implementation plan."

## Known Risks

- recipient rules are missing;
- duplicate/idempotency behavior is undefined;
- rate limits and retry policy are undefined;
- audit/event persistence requirement is unclear;
- channel scope may expand silently.

## Expected Evidence

- existing approval event model;
- recipient policy;
- notification channel requirements;
- audit/idempotency requirements.

## Expected Output Qualities

- should avoid pretending requirements are complete;
- should separate known plan from open decisions;
- should define blockers before implementation;
- should propose a minimal safe first slice.
