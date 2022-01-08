<?php

namespace BWD\Stacks\ContentList;


use BWD\Stacks\Result\ResultCollection;


interface ContentListInterface {
  /**
   * Run the actions to get the results of the list
   *
   * @param \BWD\Stacks\Result\ResultCollection $result
   *   The ResultCollection to add the results.  This should not be modified
   *
   * @return \BWD\Stacks\Result\ResultCollection
   *   This is a new instance of the result collection.
   */
  public function list(ResultCollection $result) : ResultCollection;
  /**
   * Get source, type or machine name of the playlist
   *
   * This will be used when tracking how a item as added
   * to a playlist
   *
   * @return string
   */
  public function source(): string;
}