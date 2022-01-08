<?php

namespace BWD\Stacks\Builder\Jobs;


use BWUser\User;

/**
 * @deprecated
 */
class MatchesList extends JobsList  {
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

  /**
   * Build the select query
   *
   * @param array $filters
   * @return \SelectQuery
   */
  public function buildQuery(array $filters): \SelectQuery {
    $query = parent::buildQuery($filters);

    // Add matches filter
    $alais_m = $query->innerJoin('field_data_field_match_jobref', 'm', 'n.nid = m.field_match_jobref_target_id');
    $alias_mn = $query->innerJoin('node', 'mn', "mn.nid = $alais_m.entity_id AND mn.uid = :uid", [':uid' => $this->user->getId()]);
    $alias_ms = $query->leftJoin('field_data_field_match_score', 'ms', "$alias_mn.nid = ms.entity_id");
    $query->orderBy("$alias_ms.field_match_score_value", 'DESC');

    return $query;
  }

  /**
   * Get source, type or machine name of the list
   *
   * This will be used when tracking how a item as added
   * to a playlist
   *
   * @return string
   */
  public function source(): string {
    return 'matches';
  }
}