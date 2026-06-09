# HND-001: Continuation Handoff

## Task Facts

An AI assistant partially completed a repository task:

- created a project scaffold;
- updated a workflow file;
- ran preflight successfully;
- did not run final tests;
- left uncommitted changes;
- next step is to build a benchmark package.

The user asks: "Write a handoff so I can continue in another thread."

## Known Risks

- handoff may omit current status;
- next thread may repeat completed work;
- uncommitted changes may be lost or confused with unrelated changes;
- missing verification status may create false confidence.

## Expected Evidence

- changed files list;
- gate result;
- current blocker list;
- next action;
- do-not-assume constraints.

## Expected Output Qualities

- should state completed, pending and blocked work;
- should include exact paths;
- should preserve warnings and dirty-state notes;
- should give a short ordered continuation path.
