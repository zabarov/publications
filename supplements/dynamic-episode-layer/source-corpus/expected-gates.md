# Expected Gates

## Gates

- `gate.package_boundary`: plan stays inside `pkg.auth_fixture`.
- `gate.secret_handling`: token secret is displayed once and stored as digest.
- `gate.audit_before_dispatch`: audit event persistence precedes notification
  or async dispatch.
- `gate.scope_model_review`: allowed scopes are listed as review-required if
  the fixture does not define them.
- `gate.canonical_update_boundary`: episode trace cannot update canonical graph
  state.

## Expected Blocked Alternatives

- password reset implementation;
- plaintext token storage;
- notification dispatch before audit persistence;
- code or plan outside package boundary;
- silent canonical graph update.

