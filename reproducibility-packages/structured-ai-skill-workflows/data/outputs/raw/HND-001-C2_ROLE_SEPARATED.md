# HND-001 / C2_ROLE_SEPARATED

## Status

Create a handoff, but mark it evidence-bound until live git status and verification output are attached.

## Intake

Scope: prepare continuation state after partial repository work. Desired output: a handoff that prevents rediscovery and unsafe overwrites.

## Evidence

Evidence used: task description only. Evidence missing: git status, touched files, command outputs, test results, blocker details, user decisions and exact next step.

## Risk Review

- another thread may overwrite unrelated user changes;
- partial work may be mistaken for verified completion;
- missing command output may hide a failing check;
- vague next action may restart planning instead of execution.

## Recommendation

Write a handoff with Goal, Current state, Completed, Files changed, Verification, Blockers, Next action and Safety boundary. Use exact paths and mark unknowns as unknown.

## QA

The handoff should be judged complete only when the next executor can act without reconstructing context.

## Handoff

The next thread should first read the handoff, run a fresh status check, preserve unrelated changes and execute the named next action.
