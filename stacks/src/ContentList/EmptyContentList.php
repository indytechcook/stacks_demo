<?php

namespace BWD\Stacks\ContentList;


use BWD\Stacks\Result\ResultCollection;
use BWD\Stacks\SourceableTrait;

class EmptyContentList implements ContentListInterface {
  use SourceableTrait;


  /**
   * Run the actions to get the results of the list
   *
   * @param \BWD\Stacks\Result\ResultCollection $result
   *   The ResultCollection to add the results.  This should not be modified
   *
   * @return \BWD\Stacks\Result\ResultCollection
   *   This is a new instance of the result collection.
   */
  public function list(ResultCollection $result): ResultCollection {
    return $result;
  }

}