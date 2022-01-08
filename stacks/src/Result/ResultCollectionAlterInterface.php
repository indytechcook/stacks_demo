<?php

namespace BWD\Stacks\Result;


interface ResultCollectionAlterInterface {
  /**
   * Alter the array of job ids
   *
   * @param \BWD\Stacks\Result\ResultCollection $results
   *   This object will be chagned
   */
  public function alter(ResultCollection $results);
}