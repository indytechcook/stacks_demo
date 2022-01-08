<?php

namespace BWD\Stacks\Alter\DrupalSelect;

use BWGoal\QueryAlter\GoalQueryAlterInterface;

/**
 * @deprecated
 */

class Goal implements DrupalSelectAlterInterface {

  /**
   * @var \BWGoal\QueryAlter\GoalQueryAlterInterface
   */
  private $queryAlter;

  /**
   * Goal constructor.
   *
   * @param \BWGoal\QueryAlter\GoalQueryAlterInterface $goalQueryAlter
   *   GoalQueryAlterInterface object.
   */
  public function __construct(GoalQueryAlterInterface $goalQueryAlter) {
    $this->queryAlter = $goalQueryAlter;
  }

  /**
   * Query alter.
   *
   * @param \SelectQuery $query
   *   Query.
   */
  public function alter(\SelectQuery $query) {
    $this->queryAlter->alter($query, "n.nid");
  }

}
