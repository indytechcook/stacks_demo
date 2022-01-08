<?php

namespace BWStacks\Alter\ResultCollection;

use BWD\Stacks\Alter\ResultCollection\ResultCollectionAlterInterface;
use BWD\Stacks\Result\ResultCollection;
use BWD\Stacks\Result\ResultItem;
use BWJob\Manager\JobManager;

class CuratedMatches implements ResultCollectionAlterInterface {

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
   * Alter a Result Collection Object
   *
   * @param ResultCollection $results
   */
  public function alter(ResultCollection $results) {
    $curatedJobIds = $this->job_manager->getCuratedJobIds($this->filters);
    if ($curatedJobIds) {
      $additionalResultItems = [];
      foreach ($curatedJobIds as $curatedJobId) {
        $additionalResultItems[] = new ResultItem($curatedJobId, 'curated', 'job', TRUE, 'curatedmatches');
      }
      $results->addResultItems($additionalResultItems, TRUE);
    }
  }
}
