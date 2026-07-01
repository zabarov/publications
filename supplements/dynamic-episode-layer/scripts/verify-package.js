const fs = require("fs");
const path = require("path");

const root = path.resolve(__dirname, "..");

const requiredFiles = [
  "README.md",
  "MANIFEST.md",
  "source-corpus/request.md",
  "source-corpus/requirements.md",
  "source-corpus/expected-gates.md",
  "source-corpus/technology-chain.md",
  "graph/graph-context.json",
  "outputs/baseline-output.md",
  "outputs/graph-controlled-output.md",
  "outputs/comparison-report.md",
  "trace/episode-trace.json",
  "trace/metrics.json",
];

const jsonFiles = [
  "graph/graph-context.json",
  "trace/episode-trace.json",
  "trace/metrics.json",
];

let failed = false;

for (const file of requiredFiles) {
  const fullPath = path.join(root, file);
  if (!fs.existsSync(fullPath)) {
    console.error(`missing: ${file}`);
    failed = true;
  }
}

for (const file of jsonFiles) {
  const fullPath = path.join(root, file);
  if (!fs.existsSync(fullPath)) {
    continue;
  }
  try {
    JSON.parse(fs.readFileSync(fullPath, "utf8"));
    console.log(`json ok: ${file}`);
  } catch (error) {
    console.error(`json invalid: ${file}: ${error.message}`);
    failed = true;
  }
}

if (failed) {
  process.exit(1);
}

console.log("package verification ok");
