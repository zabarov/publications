# RSK-001 / C0_COMPACT

The authentication fallback patch should not be accepted as complete without more evidence. The key risks are silent bypass of the primary authentication path, inconsistent behavior across environments, missing audit visibility and possible expansion of fallback use beyond the intended failure case.

Evidence needed: the exact diff, current authentication flow, feature flag or environment guard, tests for primary and fallback paths, audit/logging behavior and rollback plan.

Next steps: block merge until fallback scope is explicit, add tests for success/failure/disabled fallback cases, require audit logging for fallback use and document an operator-visible rollback or kill switch.

Blocker: if the fallback can authenticate a user when the primary path is unavailable without a narrow guard, the change is unsafe.
