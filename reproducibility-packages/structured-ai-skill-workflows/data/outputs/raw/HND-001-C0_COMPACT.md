# HND-001 / C0_COMPACT

The handoff should preserve enough state for another thread to continue without re-discovery. It should include goal, completed work, changed files, verification already run, open blockers, next command or edit, and safety constraints.

Evidence needed: current git status, list of touched files, commands executed, test results, unresolved errors and any user decisions.

Next steps: write a concise handoff with sections for Current state, Completed, Files changed, Verification, Blockers, Next action and Do not do. If any evidence is missing, mark it explicitly rather than filling gaps from memory.

Blocker: a handoff is incomplete if it omits exact blockers or next action.
