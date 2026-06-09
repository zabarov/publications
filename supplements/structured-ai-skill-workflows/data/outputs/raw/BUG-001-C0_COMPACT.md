# BUG-001 / C0_COMPACT

The failing build should be triaged as an environment-uncertainty issue before code changes. The main risks are chasing a local-only failure, ignoring dependency or runtime differences, and changing application code without reproducing the failure.

Evidence needed: failing command, full log, runtime versions, dependency lock state, recent changes, CI result and whether the failure reproduces in a clean environment.

Next steps: capture the exact command and log, compare local and CI environments, reinstall dependencies from lockfile, run the smallest failing test and only then decide whether the cause is code, dependency or environment.

Blocker: no code fix should be claimed until the failure is reproducible or the environment mismatch is identified.
