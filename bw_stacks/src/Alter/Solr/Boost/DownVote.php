<?php

namespace BWStacks\Alter\Solr\Boost;


use BWConfig\SolrManager;
use BWStacks\Alter\Solr\SolrAlterInterface;
use BWUser\User;

class DownVote implements SolrAlterInterface {

  /**
   * @var \BWUser\User
   */
  private $user;
  /**
   * @var array
   */
  private $downVotedJobIds;

  /**
   * DownVote constructor.
   * @param \BWUser\User $user
   * @param array $downVotedJobIds
   */
  public function __construct(User $user, array $downVotedJobIds) {

    $this->user = $user;
    $this->downVotedJobIds = $downVotedJobIds;
  }

  public function alter(SolrManager $solrManager) {
    if ($negative_boosts_tids = $this->user->boostByNegativeUserOnetBoost($this->downVotedJobIds)) {
      // Negative_boosts_tids from user object.
      foreach ($negative_boosts_tids as $tid) {
        $solrManager->buildBoostQuery('sm_field_job_onet_ref', "*:* -taxonomy_term:{$tid}", 999, FALSE);
      }
    }
  }
}