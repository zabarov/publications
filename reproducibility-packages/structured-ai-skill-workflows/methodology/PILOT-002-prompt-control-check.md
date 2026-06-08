# PILOT-002 Prompt Control Check

Date: 2026-06-08

## Purpose

Check whether the primary comparison prompts for PILOT-002 are close enough in length for the planned matched-section comparison.

## Command

```bash
for f in data/prompts/*.md; do
  printf "%s " "$f"
  sed '1d' "$f" | wc -w
done
```

## Result

| Condition | Words |
| --- | ---: |
| `C0_COMPACT` | 46 |
| `C1_SECTIONED` | 93 |
| `C2_ROLE_SEPARATED` | 100 |

## Interpretation

The primary comparison is `C1_SECTIONED` vs `C2_ROLE_SEPARATED`.

Difference:

- absolute difference: `7` words;
- relative to `C1_SECTIONED`: about `7.5%`.

This satisfies the planned +/-10 percent prompt-length rule.

`C0_COMPACT` remains intentionally shorter and should be used only as a continuity baseline against PILOT-001, not as the main causal comparison.
