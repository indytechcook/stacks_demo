<?php

namespace BWD\Stacks\Alter\Solr\Filter;


use BWConfig\SolrManager;
use BWD\Stacks\Alter\Solr\SolrAlterInterface;

/**
 * @deprecated
 */
class Bundle implements SolrAlterInterface {

  /**
   * @var string
   */
  private $bundle;

  /**
   * Bundle constructor.
   * @param $bundle
   */
  public function __construct(string $bundle = 'job') {
    $this->bundle = $bundle;
  }


  public function alterQuery(SolrManager $solr) {
    $solr->addToFq(['bundle' => $this->bundle]);
  }
}