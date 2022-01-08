<?php

namespace BWStacks\Alter\ResultCollection;

use BWD\Stacks\Alter\ResultCollection\ResultCollectionAlterInterface;
use BWD\Stacks\Result\ResultCollection;
use BWJob\Manager\JobManager;

class Viewed implements ResultCollectionAlterInterface {
  use MarkResultItemsAlteredTrait;

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
   * Alter a Result Collection Object
   *
   * @param ResultCollection $results
   */
  public function alter(ResultCollection $results) {
    $viewedJobIds = $this->job_manager->getViewedJobs();
    if ($viewedJobIds) {
      $results->moveToBack($viewedJobIds);
      $this->markResultItemsAltered($results, $viewedJobIds, 'viewed');
    }
  }
}
