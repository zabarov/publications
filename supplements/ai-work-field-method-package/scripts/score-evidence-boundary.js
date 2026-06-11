const fs = require('fs');
const path = require('path');

const root = path.resolve(__dirname, '..');
const evidencePackage = JSON.parse(fs.readFileSync(path.join(root, 'data/evidence/evidence-package-example.json'), 'utf8'));
const partialFailure = JSON.parse(fs.readFileSync(path.join(root, 'data/evidence/smoke-event-partial-failure.json'), 'utf8'));

const claims = evidencePackage.claims;
const supported = claims.filter((claim) => claim.status === 'supported').length;
const unsupported = claims.filter((claim) => claim.status === 'unsupported').length;

const output = {
  generated_at: new Date().toISOString(),
  claim_count: claims.length,
  supported_claims: supported,
  unsupported_claims: unsupported,
  boundaries: evidencePackage.boundaries,
  partial_failure_status: partialFailure.aggregate_status,
  boundary_passed: unsupported > 0 && evidencePackage.boundaries.includes('no production-quality claim')
};

fs.mkdirSync(path.join(root, 'results'), { recursive: true });
fs.writeFileSync(path.join(root, 'results/evidence-boundary-results.json'), JSON.stringify(output, null, 2) + '\n');
console.log(JSON.stringify(output, null, 2));

