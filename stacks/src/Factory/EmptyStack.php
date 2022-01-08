<?php

namespace BWD\Stacks\Factory;


use BWD\Stacks\Alter\Results\ResultAlterInterface;
use BWD\Stacks\ListCollection;
use BWD\Stacks\StackInterface;

/**
 * @deprecated
 */
class EmptyStack implements StackInterface {

  /**
   * Get an array of job ids in order
   *
   * @param array $filters
   *   Array of filters.
   * @see PlaylistManager::getFilterDefaults()
   *
   * @return array
   * - [$id => $source]
   */
  public function list(array $filters = []): array {
    return [];
  }

  /**
   * Get source, type or machine name of the playlist
   *
   * This will be used when tracking how a item as added
   * to a playlist
   *
   * @return string
   */
  public function source(): string {
    return 'empty';
  }

  /**
   * Add a ListCollection
   *
   * @param ListCollection
   */
  public function addListCollection(ListCollection $list) {
    // Do nothing
  }

  /**
   * Add a result Alter.
   *
   * @param \BWD\Stacks\Alter\Results\ResultAlterInterface $alter
   */
  public function addResultAlter(ResultAlterInterface $alter) {
    // Do nothing
  }
}