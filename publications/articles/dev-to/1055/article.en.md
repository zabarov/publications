# When One Prompt Becomes a Process: How I Split Responsibility Inside an AI Skill

I started with a simple AI prompt for developer work.

It had the usual parts: role, task, output format, a few constraints. That was
enough for small jobs:

- review this function;
- explain this error;
- suggest a plan;
- clean up this note.

Then the tasks got closer to real engineering work.

One short request changed the shape of the prompt:

```text
Review this pull request before merge.
```

That sounds simple. It is not.

A useful PR review has to read the change, understand the intent, notice
missing context, separate serious risks from small suggestions, think about
tests, and give a result that a developer can act on.

My first reaction was to keep adding rules to the prompt.

If the AI jumped to a fix too quickly, I added a rule about understanding the
task boundary first. If it mixed blockers with style comments, I added a rule
about prioritization. If it sounded confident without enough proof, I added a
rule about evidence. If the answer was correct but hard to read, I added a rule
about the final format.

Each rule was useful. The prompt itself was becoming the problem.

Everything lived in one long instruction: input analysis, implementation
review, architecture, risk, tests, and final writing. The output could still
look polished, but the actual checks were hard to see.

So I stopped treating the prompt as one large text and started treating it as a
small process.

## What I Mean By An AI Skill

By AI skill I mean a repeatable AI workflow for one kind of work.

It can be a Codex skill, a custom assistant, a system prompt, repository rules,
or another mechanism. The tool is less important than the pattern: the user
brings a recurring kind of task, and the AI handles it in a predictable way.

Examples:

- review a pull request;
- triage a bug;
- prepare a safe fix plan;
- check a release list;
- summarize a long task for handoff;
- clean up technical documentation.

For a tiny task, a short prompt is usually fine.

For repeated developer work, the prompt starts carrying more responsibility.
It has to know what counts as input, what counts as risk, what needs a test,
what can block the work, and how the final answer should help the human make a
decision.

My solution was to split those responsibilities inside the same skill.

The user still talks to one AI skill and receives one answer. Inside the skill,
the task is handled as several kinds of work.

## The Problem With A Big Prompt

A big prompt often grows from reasonable corrections.

The AI misses context, so we add a context rule. It ignores a risk, so we add a
risk rule. It writes vague advice, so we add an output rule. It forgets tests,
so we add a verification rule.

After a while, the prompt contains many good rules, but the AI still has to use
all of them at once.

For code review, that means one pass is expected to:

- read the diff;
- infer the intent;
- notice missing information;
- understand the implementation;
- check possible user impact;
- think about permissions, data, compatibility, and failure paths;
- decide what blocks merge;
- suggest tests;
- write a clear review.

That is a lot of work to hide behind one smooth answer.

The review may sound reasonable, but the developer still has to ask:

- Which comments are blockers?
- Which ones are suggestions?
- What did the AI treat as a fact?
- What is only an assumption?
- What should be tested before merge?
- Is the conclusion strong enough to act on?

When those questions are not visible in the structure, the AI answer becomes
less useful as an engineering tool.

## The Responsibility Split

I started separating the work into responsibilities inside the same AI skill.

The exact names do not matter. For developer tasks, the split often looks like
this:

| Responsibility | What It Checks |
| --- | --- |
| Input intake | What was provided, what is missing, and what cannot be assumed |
| Implementation review | Whether the change solves the stated problem |
| Action planning | What the smallest useful next step should be |
| Risk review | Data, permissions, compatibility, irreversible actions, user impact |
| Quality check | Tests, reproduction, evidence, manual verification, uncertainty |
| Final editing | A concise answer the developer can act on |

This is still one skill. The user should not have to read six separate reports.

The point is to make the internal work clearer. The final answer can stay short,
but it should carry the result of these checks: what is known, what is risky,
what blocks the decision, what needs verification, and what can wait.

## A Pull Request Review Example

A weak AI review might look like this:

```text
- Consider renaming this variable.
- Maybe add a test.
- Check permission handling.
- The code could be easier to read.
```

Each line is plausible. Together, they do not help much.

A style comment, a missing test, a possible permission issue, and a readability
note all have the same weight. The author of the pull request still has to
decide what matters before merge.

With a responsibility split, the same review can become more practical.

The input intake checks whether the AI has the diff, the task description, and
any constraints. The implementation review checks whether the change solves the
actual problem. The risk review looks for cases where a small change can affect
users, data, permissions, or compatibility. The quality check asks how the
conclusion can be verified.

The final answer might look like this:

```text
Blockers:
- After an authorization failure, the code can return a cached result. This
  can show stale or unauthorized data to the current user.

Questions:
- Is there a test for the authorization failure path?

Suggestions:
- Keep the cache fallback for technical failures, but handle access denial as
  a hard stop.

Conclusion:
- I would not merge this PR yet. First, make the authorization failure path
  explicit and cover it with a test.
```

The value comes from the order, not from making the answer longer.

The important issue has a clear place. The question is separate from the
recommendation. The suggestion does not hide the blocker. The conclusion tells
the developer what decision the review supports.

## The Same Pattern For Bug Triage

The same split helps when the request is:

```text
Here is an error. Fix it.
```

AI often wants to jump straight to the likely file and suggest a patch. That can
be useful for obvious issues. For a real bug, the useful work often starts one
step earlier.

Input intake separates facts from guesses:

- What exactly happened?
- Is there a stack trace?
- Can the issue be reproduced?
- Which environment and version are involved?
- What has already been checked?

Implementation review looks for the likely area of the cause.

Action planning chooses a small path through the code instead of turning the
bug into a broad refactor.

Risk review asks whether the fix touches data, permissions, migrations, public
APIs, background jobs, or production behavior.

Quality check asks how to prove the fix:

- a failing test before the change;
- the same test passing after the change;
- a reproduction command;
- a manual check;
- a clear note about what could not be verified.

The final answer can stay compact. It should tell the developer the cause, the
change, the verification, and the remaining risk.

That is the part an experienced engineer usually keeps in their head. The skill
just makes it explicit enough for the AI to follow.

## Why This Helps

The first benefit is fewer hidden assumptions.

When the skill has an input step, it is more likely to say what is missing
before writing a confident answer. That matters in code review and bug triage,
because a confident guess can waste more time than an honest question.

The second benefit is better prioritization.

A useful review is more than a list of possible improvements. It tells the
developer what blocks the decision, what needs an answer, and what is only a
suggestion.

The third benefit is easier improvement of the skill itself.

If all rules live in one large prompt, it is hard to see what failed. Did the AI
miss the input boundary? Did it miss the risk? Did it fail to ask for evidence?
Did it write a good technical answer in a bad format?

When responsibilities are separate, the next edit is more targeted.

If the AI invents missing facts, improve input intake. If it misses permission
risks, improve risk review. If it gives long unfocused answers, improve final
editing. The skill can grow where it actually fails.

## When I Would Use It

I would use this structure for tasks where the answer supports a decision:

- merge or hold a pull request;
- change a public API;
- fix a bug with unclear cause;
- prepare a release check;
- touch user data or permissions;
- hand off a long task to another session or person;
- review material that will be published.

For small requests, the structure becomes extra weight.

If I need a Git command, a quick explanation of a compiler error, or a small
code example, a full review process gets in the way. The skill should fit the
weight of the task.

There is also a failure mode in the other direction: roles that repeat each
other. If every responsibility says the same thing, the result is just noise.
Each responsibility should either catch a different kind of problem or make a
different decision.

## A Public Demo

I made a small public repository to show the idea:

```text
https://github.com/zabarov/demo-codex-skill-dev-review
```

It is a text-based demo skill for code review. Start with `SKILL.md`, then look
at the sample review output.

The repository is intentionally small. It shows the structure: review input,
implementation, risk, quality, and final answer. You can adapt the same pattern
to your own review workflow without copying any particular process.

## What I Took From This

The useful shift was simple: stop trying to make one perfect prompt carry every
rule in the same place.

For developer work, an AI skill becomes easier to trust when it has visible
discipline. It should know what input it has, what is still missing, where the
risk is, what needs verification, and what decision the final answer supports.

The user still owns the decision. The AI still makes mistakes. But the work is
less opaque.

For me, this is where many practical AI skills are going: from a long prompt
toward a small process with clear responsibilities inside one tool.
