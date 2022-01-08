<?php

namespace BWStacks\Alter\DrupalSelect;

use BWUser\User;

/**
 * Class PassJob.
 *
 * @package BWStacks\Alter\DrupalSelect
 */
class PassJob implements DrupalSelectAlterInterface {
  use OperatorTrait;

  /**
   * User variable.
   *
   * @var \BWUser\User
   */
  private $user;

  /**
   * PassJob constructor.
   *
   * @param \BWUser\User $user
   *   User object.
   * @param string $conditionOperator
   *   Whether to include or not job ids.
   */
  public function __construct(User $user, string $conditionOperator = 'NOT IN') {
    $this->user = $user;

    // Condition operator validation.
    if (in_array(strtoupper($conditionOperator), ["NOT IN", "IN"])) {
      $this->setOperator($conditionOperator);
    }
  }

  /**
   * Query alter.
   *
   * @param \SelectQuery $query
   *   Query.
   */
  public function alter(\SelectQuery $query) {
    $sql = <<<SQL
SELECT sr.field_stat_ref_target_id as nid, sv.`field_stat_value_value`
FROM node n
INNER JOIN field_data_field_stat_ref sr ON sr.entity_id = n.nid
INNER JOIN field_data_field_stat_type st ON st.entity_id = n.nid
INNER JOIN field_data_field_stat_value sv ON sv.entity_id = n.nid
WHERE n.type = :type
AND st.field_stat_type_value = :statType
AND sv.`field_stat_value_value` IN (:values)
AND n.uid = :uid
SQL;

    $nids = db_query($sql, [
      ':type' => 'stat',
      ':statType' => 'vote',
      ':uid' => $this->user->getId(),
      ':values' => ['down', '0'],
    ], ['target' => 'slave'])->fetchCol();

    if ($nids) {
      $query->condition('n.nid', $nids, $this->getOperator());
    }
  }

  /**
   * Get operator for query condition.
   *
   * @return string
   *   Operator.
   */
  private function getConditionOperator() {
    return $this->getOperator();
  }

}
