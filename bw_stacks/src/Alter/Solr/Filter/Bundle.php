<?php

namespace BWStacks\Alter\Solr\Filter;


use BWConfig\SolrManager;
use BWStacks\Alter\Solr\SolrAlterInterface;

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


  public function alter(SolrManager $solr) {
    $solr->addToFq(['bundle' => $this->bundle]);
  }
}