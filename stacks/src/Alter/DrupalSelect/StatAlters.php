<?php

namespace BWD\Stacks\Alter\DrupalSelect;

use BWUser\User;

/**
 * @deprecated
 */
class StatAlters implements DrupalSelectAlterInterface {
  /**
   * @var \BWUser\User
   */
  private $user;

  /**
   * @var bool
   */
  private $filter_downvotes = FALSE;
  /**
   * @var bool
   */
  private $filter_upvote = FALSE;

  /**
   * DownVote constructor.
   * @param \BWUser\User $user
   */
  public function __construct(User $user) {
    $this->user = $user;
  }

  public function alter(\SelectQuery $query) {
    if (!$this->filter_downvotes && !$this->filter_upvote) {
      return;
    }

    $sql = 'SELECT n.nid
    FROM {node} n
    INNER JOIN {field_data_field_stat_type} st ON st.entity_id = n.nid AND st.field_stat_type_value = :type
    INNER JOIN {field_data_field_stat_value} sv ON sv.entity_id = n.nid
    WHERE sv.field_stat_value_value IN (:values) AND n.uid = :uid';

    $values = [];
    if ($this->filter_downvotes && $this->filter_upvote) {
      $values = ['up', 1, 'down', '0'];
    }
    elseif ($this->filter_upvote) {
      $values = ['up', 1];
    }
    elseif ($this->filter_downvotes) {
      $values = ['down', '0'];
    }

    $nids = db_query($sql, [
      ':type' => 'vote',
      ':values' => $values,
      ':uid' => $this->user->getId(),
    ], ['target' => 'slave'])->fetchCol();

    if ($nids) {
      $query->condition('n.nid', $nids, 'NOT IN');
    }
  }

  /**
   * @param boolean $filter_downvotes
   * @return StatAlters
   */
  public function setFilterDownvotes(bool $filter_downvotes): StatAlters {
    $this->filter_downvotes = $filter_downvotes;
    return $this;
  }

  /**
   * @param boolean $filter_upvote
   * @return StatAlters
   */
  public function setFilterUpvote(bool $filter_upvote): StatAlters {
    $this->filter_upvote = $filter_upvote;
    return $this;
  }
}