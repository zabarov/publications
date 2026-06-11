# AI Work Field Controlled Measurement Protocol

Date: 2026-06-11

Status: protocol candidate

Related workflow:

- `source/workflow/2026-06-11-ai-work-field-controlled-measurement.md`

## Purpose

This protocol defines a controlled case-study measurement pass for the AI Work
Field Model. The study compares text-first AI-assisted work with
graph-field-guided AI-assisted work on the same task objective.

The current protocol does not claim that AI Work Field improves real-world
software quality. It defines what must be measured before such claims can be
considered.

## Study Type

Design-science follow-up study with controlled task-pair measurement.

Each task pair contains:

- one text-first baseline condition;
- one graph-field-guided condition;
- the same task objective;
- the same expected decision boundary;
- explicit scoring against context preservation, transition control, evidence
  boundary and handoff continuity.

## Primary Research Question

Can graph-field-guided AI-assisted development reduce working context size
while preserving task-relevant dependencies, transition constraints, evidence
boundaries and handoff state better than a text-first baseline?

## Secondary Research Questions

1. Does graph-field context projection reduce context units for the same task?
2. Does graph-field context preserve required objects and relations?
3. Does transition gating prevent invalid or premature process moves?
4. Does evidence-boundary checking reduce unsupported claims?
5. Does durable workflow state improve handoff reconstruction?
6. Do expert-review findings convert into reusable prevention mechanisms?

## Conditions

### Condition A: Text-First Baseline

The assistant receives a broad text bundle selected from notes, specifications
or documentation. The bundle may include relevant information, adjacent
information and unnecessary context.

### Condition B: Graph-Field-Guided

The assistant receives a task-specific graph context pack with selected
objects, typed relations, gates, evidence requirements and expected state
boundary.

## Unit Of Analysis

One task pair.

A task pair is valid only when both conditions use the same task objective and
the same expected output boundary.

## Minimum Fixture Fields

- task identifier;
- task objective;
- baseline context;
- graph context;
- expected relevant objects;
- expected relevant relations;
- transition boundary;
- evidence boundary;
- expected output;
- scoring record;
- limitations.

## Metrics

| Metric | Meaning |
| --- | --- |
| `baseline_context_units` | normalized size of the text-first input |
| `graph_context_units` | normalized size of the graph-field input |
| `context_reduction_percent` | relative context-size reduction |
| `required_object_recall` | required objects selected by the graph condition |
| `required_relation_recall` | required relations selected by the graph condition |
| `critical_omission_count` | missing object/relation that changes the task decision |
| `invalid_transition_detected` | whether an invalid transition was blocked |
| `unsupported_claim_count` | claims that exceed evidence boundary |
| `handoff_state_match` | whether the next state/action was reconstructed |
| `review_correction_count` | reviewer corrections needed after output |

## Core Formulas

```text
context_reduction_percent =
  (baseline_context_units - graph_context_units)
  / baseline_context_units * 100
```

```text
required_object_recall =
  selected_required_object_count / expected_required_object_count
```

```text
required_relation_recall =
  selected_required_relation_count / expected_required_relation_count
```

## Evidence Boundary

This study can support claims about measured fixture behavior and early
case-study evidence. It cannot support universal claims, independent validation
or production-quality claims until public datasets, independent reviewers and
replications are added.

## Ethics And Data Boundary

The study uses software-engineering task fixtures and public-safe or
anonymized project artifacts. It does not evaluate people, employee
performance, private communication behavior or personal data.

## Minimum Acceptance For Pilot Run

- at least one complete task pair;
- stable fixture schema;
- scoring rubric applied to the task pair;
- limitations recorded;
- all claims framed as pilot evidence.

## Minimum Acceptance For Next Paper

- at least five representative task pairs;
- consistent scoring across all task pairs;
- reviewer corrections recorded;
- analysis outputs reproducible from fixtures;
- limitations and non-independence disclosed.

