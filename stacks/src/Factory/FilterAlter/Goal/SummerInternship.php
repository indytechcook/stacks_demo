<?php

namespace BWD\Stacks\Factory\FilterAlter\Goal;

use BWD\Stacks\Factory\FilterAlter\FilterAlterInterface;

/**
 * @deprecated
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
