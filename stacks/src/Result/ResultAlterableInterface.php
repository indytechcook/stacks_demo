<?php

namespace BWD\Stacks\Result;



use BWD\Stacks\Alter\ResultCollection\ResultCollectionAlterInterface;

interface ResultAlterableInterface {
  /**
   * Add a result Alter.
   *
   * @param ResultCollectionAlterInterface $alter
   */
  public function addResultAlter(ResultCollectionAlterInterface $alter);
}