<?php

namespace BWStacks\Result;

use BWD\Stacks\Result\ResultAlterableInterface;
use BWJob\Manager\JobManager;
use BWStacks\Alter\ResultCollection\CuratedMatches;
use BWStacks\Alter\ResultCollection\DownVoted;
use BWStacks\Alter\ResultCollection\PreferredJobs;
use BWStacks\Alter\ResultCollection\Previewed;
use BWStacks\Alter\ResultCollection\Viewed;
use BWUser\User;

trait JobResultAlterTrait {
  /**
   * @var User
   */
  private $user;

  /**
   * @param \BWD\Stacks\Result\ResultAlterableInterface $list
   */
  public function addJobResultAlters(ResultAlterableInterface $list) {
    // Add curated Matches
    $job_manager = new JobManager($this->user);

    $list->addResultAlter(new CuratedMatches($job_manager));

    // Add Preferred alter
    // Cache is per company and not per user
    $org = $this->user->getOrg();
    $company_ids = $org->getPreferredCompanyIds();
    if ($company_ids) {
      $list->addResultAlter(new PreferredJobs($job_manager, $this->user));
    }

    // Move Previewed
    $list->addResultAlter(new Previewed($job_manager));
    // Move Viewed
    $list->addResultAlter(new Viewed($job_manager));
    // Remove Down Voted
    $list->addResultAlter(new DownVoted($job_manager));
  }

  /**
   * @param \BWUser\User $user
   */
  public function setUser(\BWUser\User $user) {
    $this->user = $user;
  }
}
