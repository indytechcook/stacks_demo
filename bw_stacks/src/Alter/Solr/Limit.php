<?php

namespace BWStacks\Alter\Solr;


use BWConfig\SolrManager;

class Limit implements SolrAlterInterface {
  /**
   * @var int
   */
  private $limit;

  /**
   * Range constructor.
   *
   * @param int $limit
   */
  public function __construct(int $limit) {
    $this->limit = $limit;
  }


  public function alter(SolrManager $solr) {
    $solr->setLimit($this->limit);
  }
}