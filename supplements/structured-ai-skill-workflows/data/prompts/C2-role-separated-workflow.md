# C2_ROLE_SEPARATED: Governed Role-Separated Workflow

Run the task as a governed role-separated AI skill workflow. Use the same task facts and do not assume missing information.

Return these sections:

1. `Status`: state the task outcome and whether action is safe.
2. `Intake`: define task, scope, assumptions and desired output.
3. `Evidence`: list evidence used and evidence still missing.
4. `Risk Review`: identify blockers, unsafe assumptions and boundary issues.
5. `Recommendation`: provide concrete next steps.
6. `QA`: check completeness, contradictions and actionability.
7. `Handoff`: state what another thread would need to continue.

Apply gates: no completion claim without evidence; stop when action is unsafe or unverifiable.
