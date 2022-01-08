<?php

namespace BWStacks\Alter\ResultCollection;

use BWConfig\ConfigEntityFieldQuery;
use BWD\Stacks\Alter\ResultCollection\ResultCollectionAlterInterface;
use BWD\Stacks\Result\ResultCollection;
use BWD\Stacks\Result\ResultItem;
use BWJob\Manager\JobManager;
use BWUser\User;

class PreferredJobs implements ResultCollectionAlterInterface {
  use MarkResultItemsAlteredTrait;

  /**
   * @var \BWJob\Manager\JobManager
   */
  private $job_manager;

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
   * Alter a Result Collection Object
   *
   * @param ResultCollection $results
   */
  public function alter(ResultCollection $results) {
    // Determine which jobs are of preferred companies.
    $org = $this->user->getOrg();
    $prefCompaniesIds = $org->getPreferredCompanyIds();
    $prefJobIds = [];

    $resultItems = $results->getResultItems();
    $resultIds = array_map(function(ResultItem $item) { return $item->getId(); }, $resultItems);

    if ($prefCompaniesIds) {
      $query = new ConfigEntityFieldQuery();
      $query->entityCondition('bundle', 'job');
      $query->propertyCondition('nid', $resultIds, 'IN');
      $query->fieldCondition('field_job_company_ref', 'target_id', $prefCompaniesIds, 'IN');

      $result = $query->execute();
      if ($result) {
        $prefJobIds = array_keys($result['node']);
        $results->moveToFront($prefJobIds);
        $this->markResultItemsAltered($results, $prefJobIds, 'preferredjobs');
      }
    }
  }
}
