<?php

namespace BWD\Stacks\Sequence;


use BWD\Stacks\Result\ResultCollection;

interface SequenceInterface {
  /**
   * Build a Result Collection
   *
   * @param \BWD\Stacks\Result\ResultCollection $result_collection
   * @return \BWD\Stacks\Result\ResultCollection
   */
  public function buildResultCollection(ResultCollection $result_collection) : ResultCollection;
}