<?php

namespace BWD\Stacks;

/**
 * @deprecated
 */
interface StackInterface extends ListInterface {
  /**
   * Add a ListCollection
   *
   * @param ListCollection
   */
  public function addListCollection(ListCollection $list);

}