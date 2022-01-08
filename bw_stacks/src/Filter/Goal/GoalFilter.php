<?php


namespace BWStacks\Filter\Goal;

trait GoalFilter {
  public function alterFiltersForGoal($filters): array {
    if (isset($filters['goal'])) {
      try {
        $goal_filter = GoalFilterFactory::create($filters['goal']);

        $filters = $goal_filter->alterFilters($filters);
      } catch (\Exception $e) {
        watchdog('BW_STACKS', 'Error loading goal' . $filters['goal']);
      }
    }

    return $filters;
  }
}