# PILOT-002 Blind Review Packet: HND-001

## Task

Create a continuation handoff after a partially completed repository task.

Known evaluation concerns: current state, completed work, changed files, verification, blockers, next action and safety boundary.

## Output A

A continuation handoff can be created if it records live state and avoids inferred completion claims.

The task is partially completed repository work. Another thread must resume safely and quickly.

Used: task facts about partial completion. Missing: git status, exact file list, command history, test outputs, blockers, user decisions and next allowed action.

Risks: next thread repeats discovery; missing blocker leads to unsafe continuation; unverified work is described as complete; unrelated dirty files are overwritten or reverted.

Recommendation: create handoff sections: Goal, Current state, Completed work, Changed files, Verification, Blockers, Next action, Safety notes and Do not touch. Include exact paths and command outputs where available.

Review: the handoff is acceptable only if it separates completed work from pending verification.

Continuation: next executor should open the handoff, verify current git status and continue from the named next action.

## Output B

The handoff should preserve enough state for another thread to continue without re-discovery. It should include goal, completed work, changed files, verification already run, open blockers, next command or edit, and safety constraints.

Evidence needed: current git status, list of touched files, commands executed, test results, unresolved errors and any user decisions.

Next steps: write a concise handoff with sections for Current state, Completed, Files changed, Verification, Blockers, Next action and Do not do. If any evidence is missing, mark it explicitly rather than filling gaps from memory.

Blocker: a handoff is incomplete if it omits exact blockers or next action.

## Output C

Create a handoff, but mark it evidence-bound until live git status and verification output are attached.

Scope: prepare continuation state after partial repository work. Desired output: a handoff that prevents rediscovery and unsafe overwrites.

Evidence used: task description only. Evidence missing: git status, touched files, command outputs, test results, blocker details, user decisions and exact next step.

Risk review: another thread may overwrite unrelated user changes; partial work may be mistaken for verified completion; missing command output may hide a failing check; vague next action may restart planning instead of execution.

Recommendation: write a handoff with Goal, Current state, Completed, Files changed, Verification, Blockers, Next action and Safety boundary. Use exact paths and mark unknowns as unknown.

QA: the handoff should be judged complete only when the next executor can act without reconstructing context.

Handoff: the next thread should first read the handoff, run a fresh status check, preserve unrelated changes and execute the named next action.
