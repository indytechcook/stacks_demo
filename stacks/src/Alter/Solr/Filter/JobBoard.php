<?php

namespace BWD\Stacks\Alter\Solr\Filter;


use BWConfig\SolrManager;
use BWJob\Job;
use BWD\Stacks\Alter\Solr\SolrAlterInterface;

/**
 * @deprecated
 */
class JobBoard implements SolrAlterInterface {

  /**
   * @var bool
   */
  private $exclude_jobboard;

  /**
   * JobBoard constructor.
   * @param bool $exclude_jobboard
   */
  public function __construct(bool $exclude_jobboard = FALSE) {
    $this->exclude_jobboard = $exclude_jobboard;
  }

  public function alterQuery(SolrManager $solr) {
    if ($this->exclude_jobboard) {
      $solr->addToFq(['-sm_field_job_source' => '"' . Job::SOURCE_JOBBOARD . '"']);
    }
  }
}