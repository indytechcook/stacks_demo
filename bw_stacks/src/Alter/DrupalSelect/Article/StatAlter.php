<?php

namespace BWStacks\Alter\DrupalSelect\Article;

use BWStacks\Alter\DrupalSelect\DrupalSelectAlterInterface;
use BWUser\User;

class StatAlter implements DrupalSelectAlterInterface {
  /**
   * @var bool
   */
  private $filter = TRUE;
  /**
   * @var User
   */
  private $user;

  /**
   * Favorite constructor.
   * @param User $user
   */
  public function __construct(User $user) {
    $this->user = $user;
  }

  public function alter(\SelectQuery $query) {

    // @TODO make this a stack!!!
    $sql = 'SELECT sv.field_stat_ref_target_id
    FROM {node} n
    INNER JOIN {field_data_field_stat_type} st ON st.entity_id = n.nid 
    INNER JOIN {field_data_field_stat_ref} sv ON sv.entity_id = n.nid
    WHERE n.type = \'stat\' AND n.status = 1 AND
    st.field_stat_type_value IN (:type) AND n.uid = :uid';


    $nids = db_query($sql, [
      ':type' => ['favoritearticle', 'previewedarticle', 'passedarticle', 'viewedarticle'],
      ':uid' => $this->user->getId(),
    ], ['target' => 'slave'])->fetchCol();

    if ($nids) {

      $query->condition('n.nid', $nids, 'NOT IN');
    }
  }

  /**
   * @param bool $filter
   */
  public function setFilter(bool $filter) {
    $this->filter = $filter;
  }
}