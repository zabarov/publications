# RSK-001: Authentication Fallback Review

## Task Facts

A repository patch changes authentication behavior:

- if OAuth token validation fails, the application now attempts a legacy API key fallback;
- the fallback path is enabled by default;
- tests were updated only for the success path;
- there is no migration note;
- logs include the fallback method name but not the actor id.

The user asks: "Review this patch and tell me whether it is safe to merge."

## Known Risks

- fallback enabled by default may weaken authentication boundary;
- missing negative tests for token failure and fallback denial;
- migration/operations impact is undocumented;
- logs may be insufficient for audit.

## Expected Evidence

- changed files or patch diff;
- authentication policy or threat model;
- tests covering failure and denial paths;
- deployment/migration note or feature flag policy.

## Expected Output Qualities

- should not approve merge without more evidence;
- should request or recommend negative tests;
- should surface audit logging risk;
- should suggest feature flag or disabled-by-default rollout.
