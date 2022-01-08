<?php

namespace BWD\Stacks;


use BWD\Stacks\Alter\ResultAlterable;
use BWD\Stacks\Builder\ResultAlterableInterface;

/**
 * @deprecated
 */
class ListCollectionAdapter implements StackInterface, ResultAlterableInterface {
  use ResultAlterable;

  /**
   * @var ListCollection
   */
  private $list;

  /**
   * ListCollectionAdapter constructor.
   *
   * @param ListCollection $list
   */
  public function __construct(ListCollection $list) {
    $this->list = $list;
  }


  /**
   * Get an array of job ids in order
   *
   * @param array $filters
   * @return array
   */
  public function list(array $filters = []): array {
    $results = [];

    foreach ($this->list as $list) {
      $r = $list->list($filters);

      if ($r) {
        $results += $r;
      }
    }

    foreach ($this->resultAlters as $alter) {
      $results = $alter->alter($results);
    }

    return $results;
  }

  /**
   * Get source, type or machine name of the stack
   *
   * This will be used when tracking how a item as added
   * to a playlist
   *
   * @return string
   */
  public function source(): string {
    return NULL;
  }

  /**
   * Add a ListCollection
   *
   * @param ListCollection
   */
  public function addListCollection(ListCollection $list) {
    $this->list = $list;
  }
}