const { spawnSync } = require('child_process');
const path = require('path');

const scripts = [
  'calculate-context-reduction.js',
  'validate-transitions.js',
  'score-evidence-boundary.js',
  'summarize-cockpit.js',
  'summarize-expert-review.js'
];

let failed = false;

for (const script of scripts) {
  const result = spawnSync(process.execPath, [path.join(__dirname, script)], {
    stdio: 'inherit'
  });
  if (result.status !== 0) {
    failed = true;
  }
}

if (failed) {
  process.exitCode = 1;
}

