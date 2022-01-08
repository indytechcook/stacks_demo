<?php

namespace BWD\Stacks\Result;


use BWD\Stacks\NewStackInterface;
use BWD\Stacks\SourceableTrait;

class ResultCollectionStackAdapter implements NewStackInterface {
  use ResultAlterableTrait;
  use SourceableTrait;

  /**
   * @var ResultCollection
   */
  private $results;

  /**
   * ResultCollectionStackAdapter constructor.
   * @param $results
   */
  public function __construct($results) {
    $this->results = $results;
  }


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
    $return = clone $result;
    $this->alter($return);
    return $return;
  }

}