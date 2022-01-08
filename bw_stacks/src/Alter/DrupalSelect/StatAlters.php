<?php

namespace BWStacks\Alter\DrupalSelect;

use BWUser\User;

/**
 * Class StatAlters.
 *
 * @package BWStacks\Alter\DrupalSelect
 */
class StatAlters implements DrupalSelectAlterInterface {
  /**
   * User object.
   *
   * @var \BWUser\User
   */
  private $user;

  /**
   * Add down votes.
   *
   * @var bool
   */
  private $filter_downvotes = FALSE;

  /**
   * Add up votes.
   *
   * @var bool
   */
  private $filter_upvote = FALSE;

  /**
   * StatAlters constructor.
   *
   * @param \BWUser\User $user
   */
  public function __construct(User $user) {
    $this->user = $user;
  }

  /**
   * Alter query.
   *
   * @param \SelectQuery $query
   *   Query.
   */
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
   * Set filter down votes.
   *
   * @param boolean $filter_downvotes
   *   TRUE or FALSE to inlcude it.
   *
   * @return StatAlters
   *   Stat Alters object.
   */
  public function setFilterDownvotes(bool $filter_downvotes): StatAlters {
    $this->filter_downvotes = $filter_downvotes;
    return $this;
  }

  /**
   * Set filter down votes.
   *
   * @param boolean $filter_upvote
   *   TRUE or FALSE to inlcude it.
   *
   * @return StatAlters
   *   Stat Alters object.
   */
  public function setFilterUpvote(bool $filter_upvote): StatAlters {
    $this->filter_upvote = $filter_upvote;
    return $this;
  }
}
