const fs = require('fs');
const path = require('path');

const root = path.resolve(__dirname, '..');
const tasks = JSON.parse(fs.readFileSync(path.join(root, 'data/tasks/tasks.json'), 'utf8'));
const contexts = JSON.parse(fs.readFileSync(path.join(root, 'data/tasks/expected-contexts.json'), 'utf8'));

const rows = tasks.map((task) => {
  const context = contexts.find((item) => item.task_id === task.id);
  if (!context) {
    throw new Error(`Missing expected context for ${task.id}`);
  }
  const reduction = 1 - context.graph_context_units / task.baseline_context_units;
  return {
    task_id: task.id,
    baseline_context_units: task.baseline_context_units,
    graph_context_units: context.graph_context_units,
    reduction_percent: Number((reduction * 100).toFixed(2))
  };
});

const output = {
  generated_at: new Date().toISOString(),
  package: 'ai-work-field-method-package',
  rows,
  mean_reduction_percent: Number((rows.reduce((sum, row) => sum + row.reduction_percent, 0) / rows.length).toFixed(2))
};

fs.mkdirSync(path.join(root, 'results'), { recursive: true });
fs.writeFileSync(path.join(root, 'results/context-reduction-results.json'), JSON.stringify(output, null, 2) + '\n');
console.log(JSON.stringify(output, null, 2));

