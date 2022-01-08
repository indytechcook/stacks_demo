<?php

namespace BWStacks\Filter\Goal;

use BWStacks\Filter\FilterAlterInterface;

/**
 * Class GoalSummerInternship.
 *
 * @package BWGoal.
 */
class SummerInternship implements FilterAlterInterface {

  /**
   * Alters a list of filters
   *
   * @param array $filters
   * @return array
   */
  public function alterFilters(array $filters): array {
    $filters['internship'] = TRUE;

    return $filters;
  }
}
