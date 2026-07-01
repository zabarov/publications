# Synthetic Requirements

## Package

Package ID: `pkg.auth_fixture`

Scope:

- issue scoped API tokens;
- revoke API tokens;
- record token lifecycle events in an audit log;
- expose status endpoint for token metadata;
- keep user password flow outside the package.

## Functional Requirements

- `FR-001`: create API token for an authenticated account.
- `FR-002`: assign one or more scopes to each token.
- `FR-003`: revoke a token and prevent further use.
- `FR-004`: record token-created, token-revoked and token-use-denied audit
  events.
- `FR-005`: expose token metadata without exposing token secret material.

## Constraints

- `C-001`: token secret must be shown only once at creation.
- `C-002`: token storage must use a non-reversible digest.
- `C-003`: audit event persistence must happen before external notification or
  async dispatch.
- `C-004`: generated code plan must stay within `pkg.auth_fixture`.
- `C-005`: password reset, user registration and billing integration are out of
  scope.

## Review Questions

- What token scopes are allowed?
- What audit retention period applies?
- Should revoked token metadata remain visible to administrators?

