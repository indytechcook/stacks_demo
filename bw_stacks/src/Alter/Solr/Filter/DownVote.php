<?php

namespace BWStacks\Alter\Solr\Filter;


use BWConfig\SolrManager;
use BWStacks\Alter\Solr\SolrAlterInterface;

class DownVote implements SolrAlterInterface {
  /**
   * @var array
   */
  private $down_voted_ids;

  /**
   * DownVote constructor.
   * @param array $down_voted_ids
   */
  public function __construct(array $down_voted_ids = []) {
    $this->down_voted_ids = $down_voted_ids;
  }

  public function alter(SolrManager $solr) {
    if ($this->down_voted_ids) {
      $result['-entity_id'] = implode(' OR ', $this->down_voted_ids);
      $solr->addToFq($result);
    }
  }
}