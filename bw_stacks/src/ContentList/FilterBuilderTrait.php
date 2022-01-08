<?php


namespace BWStacks\ContentList;


use BWStacks\Filter\FilterBuilder;

trait FilterBuilderTrait {
  /**
   * @var FilterBuilder
   */
  private $filters;

  /**
   * @return FilterBuilder
   */
  public function getFilters() : FilterBuilder {
    return $this->filters;
  }

  /**
   * @param FilterBuilder $filters
   */
  public function setFilters(FilterBuilder $filters) {
    $this->filters = $filters;
  }
}