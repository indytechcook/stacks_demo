<?php

namespace BWD\Stacks\Alter\Solr\Filter;


use BWConfig\SolrManager;
use BWD\Stacks\Alter\Solr\SolrAlterInterface;

/**
 * @deprecated
 */
class JobArea implements SolrAlterInterface {

  /**
   * @var array
   */
  private $job_area_tids;

  /**
   * JobArea constructor.
   *
   * @param array $job_area_tids
   */
  public function __construct(array $job_area_tids = []) {
    $this->job_area_tids = $job_area_tids;
  }

  public function alterQuery(SolrManager $solr) {
    if ($this->job_area_tids) {
      $result['sm_field_job_jobarea'] = implode(' OR ', array_map(function ($elem) {
        return '"' . 'taxonomy_term:' . $elem . '"';
      }, $this->job_area_tids));

      $solr->addToFq($result);
    }
  }
}