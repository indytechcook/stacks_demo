<?php

namespace BWStacks\Alter\Solr\Filter;


use BWConfig\SolrManager;
use BWStacks\Alter\Solr\SolrAlterInterface;

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
  public function alter(SolrManager $solr) {
    $solr->addToFq(['sm_field_taxonomy_org', 'node:' . $this->org->getId()]);
  }
}