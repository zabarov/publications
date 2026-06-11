# Data Dictionary

## Graph Objects

- `package`: a synthetic package in the platform.
- `feature`: a functional object used by one or more packages.
- `gate`: an explicit permission or quality boundary.
- `risk`: a tracked failure mode.
- `evidence`: a record that supports or limits a claim.

## Relations

- `contains`: package includes a feature.
- `requires`: object depends on another object.
- `gates`: gate controls an action or state transition.
- `blocked_by`: risk is controlled by a gate.
- `feeds`: output becomes input for another loop.
- `reviewed_by`: object is inspected by a review mechanism.

## Context Units

`context_units` are synthetic normalized units used only to demonstrate the
context-reduction calculation.

