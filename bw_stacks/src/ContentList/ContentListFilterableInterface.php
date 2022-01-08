<?php
/**
 * Created by PhpStorm.
 * User: indytechcook
 * Date: 2/16/17
 * Time: 9:56 PM
 */

namespace BWStacks\ContentList;


use BWStacks\Filter\FilterBuilder;

interface ContentListFilterableInterface {
  /**
   * Add a filter builder
   *
   * @param \BWStacks\Filter\FilterBuilder $filter
   * @return void
   */
  public function setFilters(FilterBuilder $filter);
}