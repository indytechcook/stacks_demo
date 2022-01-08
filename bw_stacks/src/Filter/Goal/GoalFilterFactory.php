<?php

namespace BWStacks\Filter\Goal;

use BWStacks\Filter\FilterAlterInterface;

class GoalFilterFactory {

  /**
   * @param string $goal
   * @return FilterAlterInterface
   *
   * @throws \Exception
   */
  public static function create(string $goal) {
    $map = self::map();

    if (!isset($map[$goal]) || !class_exists($map[$goal])) {
      throw new \Exception('Goal class does not exist ' . $goal);
    }

    return new $map[$goal]();
  }

  public static function map() {
    return [
      'firstjob' => FirstJob::class,
      'fallinternship' => FallInternShip::class,
      'summerinternship' => SummerInternship::class,
      'nextjob' => NextJob::class,
    ];
  }
}