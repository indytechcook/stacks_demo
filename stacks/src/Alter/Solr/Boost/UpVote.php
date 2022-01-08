<?php

namespace BWD\Stacks\Alter\Solr\Boost;

use BWConfig\SolrManager;
use BWD\Stacks\Alter\Solr\SolrAlterInterface;
use BWUser\User;

/**
 * @deprecated
 */
class UpVote implements SolrAlterInterface {

  /**
   * @var \BWUser\User
   */
  private $user;
  /**
   * @var array
   */
  private $upVotedJobIds;

  /**
   * DownVote constructor.
   * @param \BWUser\User $user
   * @param array $upVotedJobIds
   */
  public function __construct(User $user, array $upVotedJobIds) {
    $this->user = $user;
    $this->upVotedJobIds = $upVotedJobIds;
  }

  public function alterQuery(SolrManager $solrManager) {
    if ($positive_boosts_tids = $this->user->boostByPositiveUserOnetBoost($this->upVotedJobIds)) {
      // Positive boosts tids from user object.
      foreach ($positive_boosts_tids as $tid) {
        $solrManager->buildBoostQuery('sm_field_job_onet_ref', "taxonomy_term:{$tid}", 1.001, FALSE);
      }
    }
  }
}