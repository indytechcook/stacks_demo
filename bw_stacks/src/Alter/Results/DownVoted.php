<?php

namespace BWStacks\Alter\Results;


use BWD\Stacks\Alter\Results\ResultAlterInterface;
use BWD\Stacks\Alter\Results\ResultMergeTrait;
use BWJob\Manager\JobManager;

class DownVoted implements ResultAlterInterface {
  use ResultMergeTrait;
  /**
   * @var \BWJob\Manager\JobManager
   */
  private $job_manager;

  /**
   * DownVoted constructor.
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
  public function alter(array $results): array {
    $downvotedJobIds = $this->job_manager->getDownvotedJobIds();
    if ($downvotedJobIds) {
      $altered = $this->diffResults($results, $downvotedJobIds);

      return [$altered, array_intersect($downvotedJobIds, array_keys($altered))];
    }

    return [$results, []];
  }
}
