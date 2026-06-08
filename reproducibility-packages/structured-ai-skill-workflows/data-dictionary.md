# Data Dictionary

## Skill Corpus Row

| Field | Meaning |
| --- | --- |
| `skill_id` | Stable internal identifier for a skill or public-safe alias |
| `domain` | Domain of work, such as development, testing, content, operations or research |
| `primary_task` | Main category of task supported by the skill |
| `roles` | Explicit internal responsibilities or specialist viewpoints |
| `gates` | Quality, safety, evidence or acceptance checks |
| `stop_conditions` | Conditions that require stopping, asking the user or returning a blocker |
| `evidence_requirements` | Expected source, test, citation or artifact evidence |
| `output_sections` | Required sections in the final response or artifact |
| `handoff_support` | Whether the skill supports continuation in another thread |
| `source_boundary` | Public, private, synthetic, aggregate or mixed |
| `public_safe_status` | Allowed, redacted, aggregate-only or blocked |

## Experiment Task Row

| Field | Meaning |
| --- | --- |
| `task_id` | Stable identifier |
| `task_type` | Review, implementation planning, bug triage, publication QA or handoff |
| `input_context` | Public-safe task input |
| `baseline_prompt` | Monolithic baseline instruction |
| `role_separated_prompt` | Role-separated skill workflow instruction |
| `expected_risks` | Known risks or blockers |
| `expected_evidence` | Evidence that should be requested or produced |
| `evaluation_rubric` | Metrics used to score outputs |
