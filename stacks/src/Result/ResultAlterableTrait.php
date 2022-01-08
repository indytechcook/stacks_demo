<?php

namespace BWD\Stacks\Result;


use BWD\Stacks\Alter\ResultCollection\ResultCollectionAlterInterface;

trait ResultAlterableTrait {
  /**
   * @var ResultCollectionAlterInterface[];
   */
  protected $resultAlters = [];

  /**
   * Add a result Alter.
   *
   * @param ResultCollectionAlterInterface $alter
   */
  public function addResultAlter(ResultCollectionAlterInterface $alter) {
    $this->resultAlters[] = $alter;
  }

  /**
   * @return ResultCollectionAlterInterface[]
   */
  public function getResultAlters() : array {
    return $this->resultAlters;
  }

  /**
   * Alter Results
   *
   * @param \BWD\Stacks\Result\ResultCollection $results
   */
  public function alter(ResultCollection $results) {
    foreach ($this->getResultAlters() as $alter) {
      $alter->alter($results);
    }
  }
}