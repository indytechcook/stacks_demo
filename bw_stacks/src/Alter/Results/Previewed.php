<?php

namespace BWStacks\Alter\Results;

use BWD\Stacks\Alter\Results\Moveable;
use BWD\Stacks\Alter\Results\ResultAlterInterface;
use BWJob\Manager\JobManager;

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
   * @return array[result, affected]
   */
  public function alter(array $results) : array {
    $previewedJobIds = $this->job_manager->getPreviewedJobIds();
    if ($previewedJobIds) {
      $altered = $this->moveToBack($results, $previewedJobIds);

      return [$altered, array_intersect($previewedJobIds, array_keys($altered))];
    }

    return [$results, []];
  }
}
