# Baseline Output

Status: synthetic_baseline
Input: `03-data/fixtures/dynamic-episode-auth-fixture/request.md`

## Plan

1. Create token model with user ID, token value, scopes and revoked flag.
2. Add endpoint to create a token.
3. Add endpoint to revoke a token.
4. Add middleware to check token value.
5. Add tests for create, revoke and middleware denial.

## Assumptions

- Scope names can be free-form strings.
- Token value can be stored in the database for lookup.
- Audit logging can be added after endpoints work.
- Notification can be emitted when a token is revoked.
- Admin metadata endpoint can return all token fields except user password.

## Missing Or Weak Elements

- Package boundary is not explicitly checked.
- Plain token storage conflicts with digest-storage constraint.
- Audit persistence is delayed until after endpoint work.
- Notification ordering is not gated by audit persistence.
- Blocked alternatives are not recorded.
- Canonical update boundary is not mentioned.
- Review-required scope model is treated as an implementation assumption.

