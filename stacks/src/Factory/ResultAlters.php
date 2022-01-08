<?php

namespace BWD\Stacks\Factory;


use BWD\Stacks\Alter\Results\CuratedMatches;
use BWD\Stacks\Alter\Results\DownVoted;
use BWD\Stacks\Alter\Results\DrupalCacheResultAlterAdapter;
use BWD\Stacks\Alter\Results\PreferredJobs;
use BWD\Stacks\Alter\Results\Previewed;
use BWD\Stacks\Alter\Results\Viewed;
use BWD\Stacks\Builder\ResultAlterableInterface;
use BWJob\Manager\JobManager;
use BWUser\User;

/**
 * @deprecated
 */
trait ResultAlters {
  protected function addAlters(ResultAlterableInterface $list, User $user, array $filters = []) {
    // Add downvoted alter
    $job_manager = new JobManager($user);

    // Add curated matches
    $list->addResultAlter(new CuratedMatches($job_manager, $filters));

    // Add Prefered alter
    // Cache is per company and not per user
    $org = $user->getOrg();
    $company_ids = $org->getPreferredCompanyIds();
    if ($company_ids) {
      $alter_list = new PreferredJobs($job_manager, $user);

      $cache_id = implode('-', $company_ids);
      // Cache jobs from prefered companies
      $cache = new DrupalCacheResultAlterAdapter($alter_list, 'result-alter-companies-' . $cache_id, strtotime('+ 3 hours'));
      $list->addResultAlter($cache);
    }

    // Add Previewed
    $list->addResultAlter(new Previewed($job_manager));
    // Viewed
    $list->addResultAlter(new Viewed($job_manager));
    // Down Voated
    $list->addResultAlter(new DownVoted($job_manager));
  }
}