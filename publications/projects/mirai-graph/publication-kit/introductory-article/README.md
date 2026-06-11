# Mirai Graph: Keeping AI Workflows Under Control With Graph State

AI-assisted work often starts simply. There is a task, a prompt, a repository
and an assistant. Then the work grows. The assistant needs context. The task has
risks. A plan becomes a batch. Someone needs to decide what is approved, what is
only proposed and what still needs evidence.

At that point, the hard part is not just generating an answer. The hard part is
keeping control of the work.

Mirai Graph is a model for that control. It represents the working system as a
graph of objects, relations, evidence, readiness and gates. The graph does not
replace judgement. It gives people and AI tools a structured way to know what
state they are in, what evidence exists and what is still not allowed.

This article explains Mirai Graph through one practical workflow:

```text
seed -> graph -> context -> launch -> transition explanation -> evidence -> feedback -> kaizen
```

The workflow is synthetic and public-safe, but the pattern is practical.

## The Problem

In many AI workflows, context lives in scattered places:

- a prompt;
- a chat thread;
- a README;
- a task tracker;
- a handoff note;
- a test result;
- a reviewer comment.

Each piece may be useful. The problem is that the pieces do not automatically
form a controlled process.

An AI assistant can generate a plan. A developer can run tests. A reviewer can
leave feedback. But those events answer different questions:

- What is the current state?
- What context was used?
- What evidence exists?
- What transition is being requested?
- What is only a proposal?
- What is actually approved?

When those questions are blurred, a team can mistake generated context for
authorization, tests for acceptance, feedback for approval or a proposal for a
canonical update.

Mirai Graph is designed to keep those boundaries visible.

## The Workflow

The public Mirai Graph playground demonstrates the control loop:

```text
seed -> graph -> context -> launch -> transition explanation -> cockpit -> traceability -> multi-source feedback -> evidence -> kaizen -> baseline comparison
```

For a first explanation, we can simplify it:

```text
seed -> graph -> context -> launch -> transition explanation -> evidence -> feedback -> kaizen
```

Each step has a different job.

## Seed

A seed is the starting package for a graph.

It describes enough initial structure to create a useful graph package:
objects, relations, evidence and profile information.

In ordinary language, the seed is the first map of the working system. It does
not solve the task. It gives the task a structured starting point.

## Graph

The graph is the explicit state of the system.

Objects describe things that matter. Relations describe how they depend on each
other. Evidence connects claims to sources. Readiness records maturity and
uncertainty.

This matters because AI tools need context, but uncontrolled context is noisy.
The graph gives a way to select context from explicit state instead of pasting a
large pile of notes into a prompt.

## Context

The context pack is generated for a specific task.

In the public synthetic benchmark, the task is `task.notify_after_approval`.
The graph contains 12 objects and 8 relations. The generated context pack
selects the objects and relations relevant to that task.

The important point is the boundary: generated context is not canonical state.
It is a task-specific view of the graph.

That distinction keeps the process safer. The assistant can use the context,
but the context itself does not approve work, rewrite the graph or authorize a
release.

## Launch

A launch record describes a bounded work batch.

It says what kind of work is allowed, what files or areas are in scope, what
evidence is required and what stop conditions apply.

This is where a plan becomes controlled execution.

Without this step, an AI assistant may treat a useful plan as permission to
continue. In Mirai Graph terms, launch readiness is not implementation. It is
the point where the batch becomes defined enough to start under declared
conditions.

## Transition Explanation

A process transition asks to move from one state to another.

For example:

```text
release_ready -> released
```

Mirai Graph checks that transition against a state machine. The transition
needs required evidence. If evidence is missing, the transition should fail.

This makes process movement explainable. A transition can say:

- what state it starts from;
- what state it wants to reach;
- which transition matched;
- what evidence was required;
- what evidence was provided;
- what is still missing.

This is useful because teams often say "done" when they mean very different
things. A state machine forces the workflow to be more precise.

## Evidence

Evidence is what supports a decision.

It can be a validation result, a test report, a review note, a traceability
record or another source-backed artifact.

Evidence still does not equal approval. It supports a decision. Someone or some
governance gate still has to decide whether the evidence is enough.

This boundary is central to Mirai Graph:

- context helps work;
- evidence supports decisions;
- gates authorize transitions;
- canonical updates remain controlled.

## Feedback

Feedback can come from several sources: tests, reviewers, quality checks,
instrumentation, risk review or process checks.

The public alpha.11 materials include multi-source quality feedback and
instrumentation. The purpose is not to let metrics take over. The purpose is to
record what the signals say and classify whether anything blocks the next
transition.

A feedback report can say that the work is accepted for transition, accepted
with deferred items or blocked.

Again, the boundary matters. Feedback supports the transition decision. It does
not rewrite canonical state by itself.

## Kaizen

The loop ends with learning.

If the work produced a reusable lesson, that lesson should be captured as an
improvement item. If there is no reusable lesson, the workflow should say so.

This prevents every batch from disappearing after completion. The system can
learn without silently changing its own rules.

## What This Gives

The value of Mirai Graph is not that it makes AI "smarter" in a vague way.

It makes the work more inspectable.

You can see what context was selected. You can see which transition was
requested. You can see what evidence exists. You can see what feedback said.
You can see whether a gate has actually approved movement.

That is useful in human-AI workflows because the weak point is often not
generation. The weak point is uncontrolled movement from suggestion to action.

## What It Does Not Claim

The public Mirai Graph examples are synthetic and bounded.

They do not prove broad scientific effectiveness. They do not claim production
autonomy. They do not say that evidence authorizes updates automatically. They
do not publish private SIMAI routing, raw skill sources, internal logs or
customer data.

This boundary is important. Mirai Graph is useful as a practical operating
model, but public claims should stay inside the public alpha evidence.

## A Simple Way To Think About It

Mirai Graph separates four things that are often mixed together:

- context;
- evidence;
- approval;
- canonical state.

A generated context pack helps the assistant work.

Evidence shows what happened or what was checked.

A gate decides whether movement is allowed.

Canonical state changes only through controlled update.

That separation is the core idea.

## What Comes Next

The next public step is to keep explaining Mirai Graph through concrete
workflows.

One article can cover implementation control. Another can cover skill runtime.
Another can cover organization governance. The standard is broad, but readers
should not have to understand all of it before seeing why the model exists.

The first useful message is simple: AI work needs state, evidence and gates.
Mirai Graph gives those things a public, inspectable shape.
