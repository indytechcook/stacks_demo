<?php

namespace BWStacks\ListOf;


trait StatsRemovableTrait {
  /**
   * @var bool
   */
  private $include_stat_alters = TRUE;

  /**
   * @param bool $include_stat_alters
   */
  public function setIncludeStatAlters(bool $include_stat_alters) {
    $this->include_stat_alters = $include_stat_alters;
  }

  /**
   * @return bool
   */
  public function isIncludeStatAlters(): bool {
    return $this->include_stat_alters;
  }
}