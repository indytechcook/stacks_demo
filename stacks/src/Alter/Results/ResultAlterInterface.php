<?php

namespace BWD\Stacks\Alter\Results;

/**
 * @deprecated
 */
interface ResultAlterInterface {
  /**
   * Alter the array of job ids
   *
   * @param array $results
   * - [$id => $source]
   *
   * @return array
   * - [$id => $source]
   */
  public function alter(array $results) : array;
}