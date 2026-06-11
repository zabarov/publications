const fs = require('fs');
const path = require('path');

const root = path.resolve(__dirname, '..');
const dir = path.join(root, 'data/cockpit');
const snapshots = fs.readdirSync(dir)
  .filter((file) => file.endsWith('.json'))
  .sort()
  .map((file) => JSON.parse(fs.readFileSync(path.join(dir, file), 'utf8')));

const output = {
  generated_at: new Date().toISOString(),
  snapshot_count: snapshots.length,
  status_caps: snapshots.map((item) => item.status_cap),
  production_release_allowed_anywhere: snapshots.some((item) => item.production_release_allowed),
  readiness_delta: Number((snapshots[snapshots.length - 1].readiness_score - snapshots[0].readiness_score).toFixed(2)),
  evidence_completeness_delta: Number((snapshots[snapshots.length - 1].evidence_completeness - snapshots[0].evidence_completeness).toFixed(2))
};

fs.mkdirSync(path.join(root, 'results'), { recursive: true });
fs.writeFileSync(path.join(root, 'results/cockpit-summary.json'), JSON.stringify(output, null, 2) + '\n');
console.log(JSON.stringify(output, null, 2));

