<?php

namespace BWD\Stacks\Alter\Solr\Boost;


use BWConfig\SolrManager;
use BWD\Stacks\Alter\Solr\SolrAlterInterface;

/**
 * @deprecated
 */
class SourceBoost implements SolrAlterInterface {

  const BOOST = [
    [
      'field' => 'sm_field_job_source',
      'term' => 'Company',
      'priority' => 1.01,
    ],
    [
      'field' => 'sm_field_job_source',
      'term' => 'Education',
      'priority' => 1.01,
    ],
    [
      'field' => 'sm_field_job_source',
      'term' => 'Government',
      'priority' => 1.01,
    ],
  ];
  /**
   * @var array
   */
  private $boost;

  /**
   * SourceBoost constructor.
   * @param array $boost
   */
  public function __construct(array $boost = self::BOOST) {
    $this->boost = $boost;
  }

  public function alterQuery(SolrManager $solr) {
    if (!empty($this->boost)) {
      foreach ($this->boost as $value) {
        $solr->addToBQ($solr->buildBoostQuery($value['field'], $value['term'], $value['priority'], FALSE));
      }
    }
  }
}