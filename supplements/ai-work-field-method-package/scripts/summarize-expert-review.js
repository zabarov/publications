const fs = require('fs');
const path = require('path');

const root = path.resolve(__dirname, '..');
const findings = JSON.parse(fs.readFileSync(path.join(root, 'data/expert-review/findings.json'), 'utf8'));
const mechanisms = JSON.parse(fs.readFileSync(path.join(root, 'data/expert-review/prevention-mechanisms.json'), 'utf8'));

const mechanismIds = new Set(mechanisms.map((item) => item.id));
const rows = findings.map((finding) => ({
  finding_id: finding.id,
  severity: finding.severity,
  prevention_mechanism: finding.prevention_mechanism,
  mechanism_defined: mechanismIds.has(finding.prevention_mechanism)
}));

const output = {
  generated_at: new Date().toISOString(),
  finding_count: findings.length,
  mechanism_count: mechanisms.length,
  all_findings_have_mechanisms: rows.every((row) => row.mechanism_defined),
  rows
};

fs.mkdirSync(path.join(root, 'results'), { recursive: true });
fs.writeFileSync(path.join(root, 'results/expert-review-summary.json'), JSON.stringify(output, null, 2) + '\n');
console.log(JSON.stringify(output, null, 2));
if (!output.all_findings_have_mechanisms) {
  process.exitCode = 1;
}

