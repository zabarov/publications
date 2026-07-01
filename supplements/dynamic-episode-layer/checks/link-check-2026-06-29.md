# Link Check: Dynamic Episode v0.2

Status: pass
Date: 2026-06-29
Method: `curl -A "Mozilla/5.0" -L -s -o /dev/null -w "%{http_code}"`

| HTTP | URL | Role |
| --- | --- | --- |
| 200 | https://arxiv.org/abs/2005.11401 | RAG reference |
| 200 | https://arxiv.org/abs/2310.06770 | SWE-bench reference |
| 200 | https://arxiv.org/abs/2404.16130 | GraphRAG reference |
| 200 | https://www.computer.org/csdl/proceedings-article/icre/1994/00292398/12OmNzmLxRE | Requirements traceability reference |
| 200 | https://doi.org/10.1007/978-3-642-28108-2_19 | Process mining manifesto reference |
| 200 | https://aisel.aisnet.org/misq/vol28/iss1/6/ | Design-science reference |
| 200 | https://orcid.org/0009-0004-8605-5594 | Author ORCID |
| 200 | https://www.nist.gov/itl/ai-risk-management-framework | AI RMF reference |

## Notes

The Gotel and Finkelstein reference uses the IEEE Computer Society record in the
public manuscript because it returned HTTP 200 in live link checks. The source
matrix also records that UCL Discovery provides metadata/PDF mirror evidence.
