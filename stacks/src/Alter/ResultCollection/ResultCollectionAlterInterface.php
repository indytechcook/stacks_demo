<?php

namespace BWD\Stacks\Alter\ResultCollection;


use BWD\Stacks\Result\ResultCollection;

interface ResultCollectionAlterInterface {
  /**
   * Alter a Result Collection Object
   *
   * @param \BWD\Stacks\Result\ResultCollection $results
   */
  public function alter(ResultCollection $results);
}