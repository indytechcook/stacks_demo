<?php

namespace BWD\Stacks\Alter\Solr;

// @TODO move this dependency out of drupal
use BWConfig\SolrManager;

/**
 * @deprecated
 */
interface SolrAlterInterface {
  public function alterQuery(SolrManager $solr);
}