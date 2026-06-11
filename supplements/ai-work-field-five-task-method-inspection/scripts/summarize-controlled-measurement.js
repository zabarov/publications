const fs = require('fs');
const path = require('path');

const root = path.resolve(__dirname, '..');
const fixturesDir = path.join(root, 'fixtures');
const executionsDir = path.join(root, 'executions');
const resultsDir = path.join(root, 'results');
const outputFile = path.join(resultsDir, 'controlled-measurement-summary.json');

function percent(value) {
  return Math.round(value * 100) / 100;
}

function recall(selected, expected) {
  if (!expected.length) {
    return null;
  }
  const selectedSet = new Set(selected);
  const hitCount = expected.filter((item) => selectedSet.has(item)).length;
  return {
    hit_count: hitCount,
    expected_count: expected.length,
    value: percent(hitCount / expected.length)
  };
}

function summarizeFixture(file) {
  const fixture = JSON.parse(fs.readFileSync(file, 'utf8'));
  const executionFile = path.join(executionsDir, `${fixture.fixture_id}-execution.json`);
  const execution = fs.existsSync(executionFile)
    ? JSON.parse(fs.readFileSync(executionFile, 'utf8'))
    : null;
  const scoring = execution ? execution.scoring.graph_field_guided : fixture.scoring;
  const baselineUnits = fixture.conditions.text_first_baseline.context_units;
  const graphUnits = fixture.conditions.graph_field_guided.context_units;
  const contextReduction = percent(((baselineUnits - graphUnits) / baselineUnits) * 100);
  const objectRecall = recall(
    fixture.conditions.graph_field_guided.selected_objects,
    fixture.expected.required_objects
  );
  const relationRecall = recall(
    fixture.conditions.graph_field_guided.selected_relations,
    fixture.expected.required_relations
  );
  const criticalOmissionCount =
    (objectRecall.expected_count - objectRecall.hit_count) +
    (relationRecall.expected_count - relationRecall.hit_count);

  return {
    fixture_id: fixture.fixture_id,
    baseline_context_units: baselineUnits,
    graph_context_units: graphUnits,
    context_reduction_percent: contextReduction,
    required_object_recall: objectRecall.value,
    required_relation_recall: relationRecall.value,
    critical_omission_count: criticalOmissionCount,
    invalid_transition_detected: scoring.invalid_transition_detected,
    unsupported_claim_count: scoring.unsupported_claim_count,
    handoff_state_match: scoring.handoff_state_match,
    review_correction_count: scoring.review_correction_count,
    execution_record_present: Boolean(execution),
    baseline_condition_score: execution ? execution.scoring.text_first_baseline : null,
    graph_condition_score: execution ? execution.scoring.graph_field_guided : null,
    reviewer_mode: execution ? execution.reviewer.mode : 'not_scored',
    verdict: execution ? execution.verdict : 'measurement-ready'
  };
}

const fixtureFiles = fs.readdirSync(fixturesDir)
  .filter((file) => file.endsWith('.json'))
  .sort()
  .map((file) => path.join(fixturesDir, file));

const rows = fixtureFiles.map(summarizeFixture);
const meanContextReduction = rows.length
  ? percent(rows.reduce((sum, row) => sum + row.context_reduction_percent, 0) / rows.length)
  : 0;
const executionRecordCount = rows.filter((row) => row.execution_record_present).length;
const graphPilotPassCount = rows.filter((row) => row.graph_condition_score && row.graph_condition_score.score === 'pilot-pass').length;
const baselinePilotFailCount = rows.filter((row) => row.baseline_condition_score && row.baseline_condition_score.score === 'pilot-fail').length;
const reviewerModes = Array.from(new Set(rows.map((row) => row.reviewer_mode))).sort();
const summary = {
  generated_at: new Date().toISOString(),
  package: 'ai-work-field-controlled-measurement',
  fixture_count: rows.length,
  execution_record_count: executionRecordCount,
  mean_context_reduction_percent: meanContextReduction,
  graph_pilot_pass_count: graphPilotPassCount,
  baseline_pilot_fail_count: baselinePilotFailCount,
  reviewer_modes: reviewerModes,
  rows,
  claim_boundary: 'Internal non-independent pilot scoring only; not empirical validation.'
};

fs.mkdirSync(resultsDir, { recursive: true });
fs.writeFileSync(outputFile, `${JSON.stringify(summary, null, 2)}\n`);
console.log(outputFile);
