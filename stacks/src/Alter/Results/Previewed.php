<?php

namespace BWD\Stacks\Alter\Results;

use BWJob\Manager\JobManager;

/**
 * @deprecated
 */
class Previewed implements ResultAlterInterface  {
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
  public function alter(array $results) : array {
    $previewedJobIds = $this->job_manager->getPreviewedJobIds();
    if ($previewedJobIds) {
      return $this->moveToBack($results, $previewedJobIds);
    }
    return $results;
  }
}