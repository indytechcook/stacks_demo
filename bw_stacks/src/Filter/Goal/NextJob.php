<?php

namespace BWStacks\Filter\Goal;

use BWStacks\Filter\FilterAlterInterface;

/**
 * Class GoalSummerInternship.
 *
 * @package BWGoal.
 */
class NextJob implements FilterAlterInterface {

  /**
   * Alters a list of filters
   *
   * @param array $filters
   * @return array
   */
  public function alterFilters(array $filters): array {
    $filters['internship'] = FALSE;

    return $filters;
  }
}
