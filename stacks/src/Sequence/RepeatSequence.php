<?php

namespace BWD\Stacks\Sequence;

use BWD\Stacks\Result\ResultCollection;

/**
 * Class RepeatSequence
 *
 * Repeats a SimpleSequence until max is met or content runs out
 *
 * @package BWD\Stacks\Sequence
 */
class RepeatSequence extends SimpleSequence {
  /**
   * @var int
   */
  private $iterations = 5;

  public function buildResultCollection(ResultCollection $result_collection): ResultCollection {
    $org_sequence = $this->sequence;

    // Repeat sequence of the number of iterations
    for ($i = 0; $i < $this->iterations; $i++) {
      foreach ($org_sequence as $obj_hash => $number) {
        if ($obj = $this->getObjectFromHash($obj_hash)) {
          $this->addList($obj, $number);
        }
      }
    }

    return parent::buildResultCollection($result_collection);
  }

  /**
   * @param int $iterations
   */
  public function setIterations(int $iterations) {
    $this->iterations = $iterations;
  }
}