<?php

namespace BWD\Stacks\Builder\Solr;


use BWConfig\SolrManager;
use BWD\Stacks\ListInterface;

/**
 * @deprecated
 */
interface SolrBuilderInterface extends ListInterface  {
  /**
   * Build out the Solr query necessary
   *
   * @param array $filters
   * @return \BWConfig\SolrManager
   */
  public function buildQuery(array $filters = []): SolrManager;
}