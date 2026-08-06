const fs = require('fs');
const path = require('path');
const { execFileSync } = require('child_process');

const root = path.resolve(__dirname, '..');

const requiredFiles = [
  'graph.json',
  'graph/dna/graph-dna.json',
  'graph/specs/index.json',
  'graph/specs/objects/repository-zones.json',
  'graph/specs/objects/publication-registry.json',
  'graph/specs/objects/publication-series.json',
  'graph/specs/policies/public-boundary-policy.json',
  'graph/specs/policies/local-source-policy.json',
  'graph/specs/policies/full-text-mirroring-policy.json',
  'graph/specs/scenarios/publication-lifecycle.json',
  'graph/specs/relations/operating-mode-relations.json',
  'graph/specs/evidence/repository-evidence.json',
  'graph/docs/guides/operating-mode.md'
];

function readJson(relativePath) {
  const fullPath = path.join(root, relativePath);
  if (!fs.existsSync(fullPath)) {
    throw new Error(`Missing required graph file: ${relativePath}`);
  }
  return JSON.parse(fs.readFileSync(fullPath, 'utf8'));
}

function assert(condition, message) {
  if (!condition) {
    throw new Error(message);
  }
}

function git(args) {
  return execFileSync('git', args, {
    cwd: root,
    encoding: 'utf8',
    stdio: ['ignore', 'pipe', 'pipe']
  }).trim();
}

for (const file of requiredFiles) {
  if (file.endsWith('.json')) {
    const json = readJson(file);
    assert(json.schema_version, `${file} must define schema_version`);
    assert(json.id || json.graph_id, `${file} must define id or graph_id`);
  } else {
    assert(fs.existsSync(path.join(root, file)), `Missing required graph file: ${file}`);
  }
}

const manifest = readJson('graph.json');
assert(manifest.format === 'mirai-graph' && manifest.schema_version === '2.0.0', 'Root manifest must use Mirai Graph v2');
assert(manifest.id === 'rim-zabarov.publications', 'Unexpected manifest id');
assert(manifest.public_safety.public_repo === true, 'Graph must mark repository as public');
assert(manifest.public_safety.ignored_raw_workspace === 'source/', 'Graph must keep source/ as ignored raw workspace');

const ignoreResult = git(['check-ignore', '-v', 'source/.mirai-graph-ignore-probe']);
assert(ignoreResult.includes('source/'), 'source/ must be ignored by Git');

const trackedSource = git(['ls-files', 'source']);
assert(trackedSource === '', 'No files under source/ may be tracked');

const policy = readJson('graph/specs/policies/full-text-mirroring-policy.json');
assert(policy.default_policy === 'canonical_only', 'Full-text mirroring must default to canonical_only');

console.log('Publication graph validation passed.');
