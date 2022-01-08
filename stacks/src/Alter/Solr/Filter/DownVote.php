<?php

namespace BWD\Stacks\Alter\Solr\Filter;


use BWConfig\SolrManager;
use BWD\Stacks\Alter\Solr\SolrAlterInterface;

/**
 * @deprecated
 */
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

  public function alterQuery(SolrManager $solr) {
    if ($this->down_voted_ids) {
      $result['-entity_id'] = implode(' OR ', $this->down_voted_ids);
      $solr->addToFq($result);
    }
  }
}