<?php

namespace BWStacks\ContentList\DrupalSelect;

use BWStacks\Alter\DrupalSelect\DrupalSelectAlterInterface;
use SelectQuery;

/**
 * Class Alterable
 * @package BWStacks\ContentList
 *
 * Provides methods for DrupalSelectInterface lists
 */
trait DrupalSelectAlterable {

  /**
   * @var DrupalSelectAlterInterface[]
   */
  protected $alters = [];


  /**
   * Apply alters to a query
   *
   * @param \SelectQuery $query
   * @return mixed
   */
  public function applyAlters(SelectQuery $query) {
    /** @var DrupalSelectAlterInterface $alter */
    foreach ($this->alters as $alter) {
      $alter->alter($query);
    }
  }


  /**
   * Add a Alter to the query
   *
   * @param \BWStacks\Alter\DrupalSelect\DrupalSelectAlterInterface $alter
   * @return mixed
   */
  public function addAlter(DrupalSelectAlterInterface $alter) {
    $this->alters[] = $alter;
  }
}