# Graph-Controlled Output

Status: synthetic_graph_controlled
Input: `03-data/fixtures/dynamic-episode-auth-fixture/request.md`
Graph context: `03-data/fixtures/dynamic-episode-auth-fixture/graph-context.json`

## Activated Context

- `pkg.auth_fixture`
- `feature.issue_token`
- `feature.revoke_token`
- `feature.audit_token_events`
- `constraint.secret_once`
- `constraint.digest_storage`
- `gate.audit_before_dispatch`
- `boundary.package_scope`
- `risk.unsupported_scope_model`

## Selected Implementation Order

1. Confirm `pkg.auth_fixture` boundary.
2. Define token object with account reference, digest, scopes, created time,
   revoked time and last-used metadata.
3. Define token creation flow:
   - generate secret;
   - store digest only;
   - return secret once;
   - record token-created audit event.
4. Define token revocation flow:
   - mark token revoked;
   - persist token-revoked audit event;
   - only then allow notification or async dispatch.
5. Define token metadata endpoint that excludes secret material and digest.
6. Define tests for issue, revoke, denied use, audit ordering and boundary
   exclusions.
7. Mark allowed scope model as review-required.

## Blocked Alternatives

- `implement_password_reset`: outside package scope.
- `store_plaintext_token`: violates digest-storage constraint.
- `dispatch_notification_before_audit`: violates audit-before-dispatch gate.
- `write_outside_package_scope`: violates package boundary.
- `treat_generated_context_as_canonical_update`: violates canonical update
  boundary.

## Findings

- Work fix: baseline token storage should be replaced with digest-only storage.
- Test gap: audit ordering requires an explicit test.
- Spec gap: allowed token scopes require review.
- Process improvement: baseline run did not record blocked alternatives.
- Graph proposal: add standard token-secret handling pattern to future auth
  fixtures if repeated.

