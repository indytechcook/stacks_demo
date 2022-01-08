<?php

namespace BWStacks\Alter\DrupalSelect;


use BWUser\User;

class MatchForJobs implements DrupalSelectAlterInterface {
  /**
   * @var \BWUser\User
   */
  private $user;

  /**
   * MatchesPlaylist constructor.
   *
   * @param \BWUser\User $user
   */
  public function __construct(User $user) {
    $this->user = $user;
  }

  public function alter(\SelectQuery $query) {
    $alais_m = $query->innerJoin('field_data_field_match_jobref', 'm', 'n.nid = m.field_match_jobref_target_id');
    $alias_mn = $query->innerJoin('node', 'mn', "mn.nid = $alais_m.entity_id AND mn.uid = :uid", [':uid' => $this->user->getId()]);
    $alias_ms = $query->leftJoin('field_data_field_match_score', 'ms', "$alias_mn.nid = ms.entity_id");
    $query->orderBy("$alias_ms.field_match_score_value", 'DESC');
  }
}