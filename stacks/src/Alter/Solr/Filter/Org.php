<?php

namespace BWD\Stacks\Alter\Solr\Filter;


use BWConfig\SolrManager;
use BWD\Stacks\Alter\Solr\SolrAlterInterface;

/**
 * @deprecated
 */
class Org implements SolrAlterInterface {

  /**
   * @var \BWOrg\Org
   */
  private $org;

  /**
   * Org constructor.
   * @param $org
   */
  public function __construct($org) {
    $this->org = $org;
  }


  /**
   * @param \BWConfig\SolrManager $solr
   */
  public function alterQuery(SolrManager $solr) {
    $solr->addToFq(['sm_field_taxonomy_org', 'node:' . $this->org->getId()]);
  }
}