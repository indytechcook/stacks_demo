<?php

namespace BWD\Stacks\Sequence;

use BWD\Stacks\NewStackInterface;
use BWD\Stacks\Result\ResultAlterableTrait;
use BWD\Stacks\Result\ResultCollection;


class SequenceAdapter implements NewStackInterface {
  use ResultAlterableTrait;

  /**
   * @var \BWD\Stacks\Sequence\SequenceInterface
   */
  private $sequence;

  /**
   * SequenceAdapter constructor.
   * @param \BWD\Stacks\Sequence\SequenceInterface $sequence
   */
  public function __construct(SequenceInterface $sequence) {
    $this->sequence = $sequence;
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
  public function list(ResultCollection $result) : ResultCollection {
    $results = $this->sequence->buildResultCollection($result);
    $this->alter($results);
    return $results;
  }

  /**
   * Get source, type or machine name of the playlist
   *
   * This will be used when tracking how a item as added
   * to a playlist
   *
   * @return string
   */
  public function source(): string {
    return 'sequence_adapter';
  }

}