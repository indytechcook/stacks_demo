<?php

namespace BWStacks\Alter\DrupalSelect;


use BWStacks\StacksConfig;

class Range implements DrupalSelectAlterInterface {
  /**
   * @var int
   */
  private $start;
  /**
   * @var int
   */
  private $end;

  /**
   * Range constructor.
   *
   * @param int $start
   * @param int $end
   */
  public function __construct(int $start, int $end) {
    $this->start = $start;
    $this->end = $end;
  }


  public function alter(\SelectQuery $query) {
    $start = $this->start ?? 0;
    $end = $this->end ?? StacksConfig::QUERY_LIMIT;

    if ($end) {
      $query->range($start, $end);
    }
  }
}
