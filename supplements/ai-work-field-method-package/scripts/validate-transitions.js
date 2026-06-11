const fs = require('fs');
const path = require('path');

const root = path.resolve(__dirname, '..');
const fixtures = JSON.parse(fs.readFileSync(path.join(root, 'data/cycles/transition-fixtures.json'), 'utf8'));

const required = {
  'ready_to_code->coding_started': ['launch_record', 'allowed_files'],
  'coding_evidence_collected->review_ready': ['test_summary', 'changed_files', 'risk_summary'],
  'review_ready->released': ['release_evidence', 'approval_record']
};

function actualAllowed(fixture) {
  const key = `${fixture.from}->${fixture.to}`;
  if (!required[key]) {
    return false;
  }
  return required[key].every((item) => fixture.evidence.includes(item));
}

const rows = fixtures.map((fixture) => {
  const allowed = actualAllowed(fixture);
  return {
    fixture_id: fixture.id,
    expected_allowed: fixture.expected_allowed,
    actual_allowed: allowed,
    passed: fixture.expected_allowed === allowed
  };
});

const output = {
  generated_at: new Date().toISOString(),
  passed: rows.every((row) => row.passed),
  rows
};

fs.mkdirSync(path.join(root, 'results'), { recursive: true });
fs.writeFileSync(path.join(root, 'results/transition-validation-results.json'), JSON.stringify(output, null, 2) + '\n');
console.log(JSON.stringify(output, null, 2));
if (!output.passed) {
  process.exitCode = 1;
}

