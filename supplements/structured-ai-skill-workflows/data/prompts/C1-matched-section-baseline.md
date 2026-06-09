# C1_SECTIONED: Matched-Section Baseline

Solve the task as a structured AI assistant. Use the same task facts and do not assume missing information.

Return these sections:

1. `Status`: state the task outcome and whether action is safe.
2. `Context`: summarize relevant facts and assumptions.
3. `Evidence`: list evidence used and evidence still missing.
4. `Risks`: identify blockers, unsafe assumptions and boundary issues.
5. `Recommendation`: provide concrete next steps.
6. `Review`: check completeness, contradictions and actionability.
7. `Continuation`: state what another thread would need to continue.

Keep the answer concise, specific and grounded in the provided task facts.
