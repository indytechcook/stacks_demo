<?php

namespace BWStacks\Alter\Results;


use BWConfig\ConfigEntityFieldQuery;
use BWD\Stacks\Alter\Results\Moveable;
use BWD\Stacks\Alter\Results\ResultAlterInterface;
use BWJob\Manager\JobManager;
use BWUser\User;

class PreferredJobs implements ResultAlterInterface {
  use Moveable;

  /**
   * @var \BWJob\Manager\JobManager
   */
  private $job_manager;
  /**
   * @var array
   */
  private $filters;
  /**
   * @var \BWUser\User
   */
  private $user;

  /**
   * CuratedMatches constructor.
   *
   * @param \BWJob\Manager\JobManager $job_manager
   * @param \BWUser\User $user
   */
  public function __construct(JobManager $job_manager, User $user) {
    $this->job_manager = $job_manager;
    $this->user = $user;
  }

  /**
   * Alter the array of job ids
   *
   * @param array $results
   * @return array[result, affected]
   */
  public function alter(array $results): array {
    // Determine which jobs are of preferred companies.
    $org = $this->user->getOrg();
    $prefCompaniesIds = $org->getPreferredCompanyIds();
    $prefJobIds = [];

    if ($prefCompaniesIds) {
      $query = new ConfigEntityFieldQuery();
      $query->entityCondition('bundle', 'job');
      $query->propertyCondition('nid', $results, 'IN');
      $query->fieldCondition('field_job_company_ref', 'target_id', $prefCompaniesIds, 'IN');

      $result = $query->execute();
      if ($result) {
        $prefJobIds = array_keys($result['node']);
        $results = $this->moveToFront($results, $prefJobIds);
      }
    }

    return [$results, $prefJobIds];
  }
}
