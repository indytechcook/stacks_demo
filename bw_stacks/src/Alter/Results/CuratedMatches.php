<?php

namespace BWStacks\Alter\Results;

use BWD\Stacks\Alter\Results\ResultAlterInterface;
use BWD\Stacks\Alter\Results\ResultMergeTrait;
use BWJob\Manager\JobManager;

class CuratedMatches implements ResultAlterInterface {
  use ResultMergeTrait;

  /**
   * @var \BWJob\Manager\JobManager
   */
  private $job_manager;
  /**
   * @var array
   */
  private $filters;

  /**
   * CuratedMatches constructor.
   *
   * @param \BWJob\Manager\JobManager $job_manager
   * @param array $filters
   */
  public function __construct(JobManager $job_manager, array $filters = []) {
    $this->job_manager = $job_manager;
    $this->filters = $filters;
  }

  /**
   * Alter the array of job ids
   *
   * @param array $results
   * @return array[result, affected]
   */
  public function alter(array $results) : array {
    $curatedJobIds = $this->job_manager->getCuratedJobIds($this->filters);
    if ($curatedJobIds) {
      $results = $this->mergeResults($results, $curatedJobIds, 'curated', TRUE);
    }

    return [$results, $curatedJobIds];
  }
}
