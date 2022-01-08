<?php

namespace BWD\Stacks\Alter\Results;


use BWJob\Manager\JobManager;

/**
 * @deprecated
 */
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
   * @return array
   */
  public function alter(array $results): array {
    $downvotedJobIds = $this->job_manager->getDownvotedJobIds();
    if ($downvotedJobIds) {
      return $this->diffResults($results, $downvotedJobIds);
    }
    return $results;
  }
}