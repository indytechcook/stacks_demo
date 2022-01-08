<?php

namespace BWD\Stacks\Builder;

use BWD\Stacks\ListInterface;

/**
 * @deprecated
 */
interface DrupalSelectListInterface extends ListInterface   {

  /**
   * Build the query
   *
   * @param array $filters
   * @return \SelectQuery
   */
  public function buildQuery(array $filters) : \SelectQuery;
}