<?php

namespace BWD\Stacks\Alter\Results;

use BWJob\Manager\JobManager;

/**
 * @deprecated
 */
class Viewed implements ResultAlterInterface {
  use Moveable;

  /**
   * @var \BWJob\Manager\JobManager
   */
  private $job_manager;

  /**
   * Previewed constructor.
   * @param \BWJob\Manager\JobManager $job_manager
   */
  public function __construct(JobManager $job_manager) {
    $this->job_manager = $job_manager;
  }

  /**
   * Alter the array of job ids
   *
   * @param array $results
   * @return array
   */
  public function alter(array $results): array {
    $viewedJobIds = $this->job_manager->getViewedJobs();
    if ($viewedJobIds) {
      return $this->moveToBack($results, $viewedJobIds);
    }
    return $results;
  }
}