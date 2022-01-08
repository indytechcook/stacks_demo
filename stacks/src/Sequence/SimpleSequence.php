<?php

namespace BWD\Stacks\Sequence;


use BWD\Stacks\ContentList\ContentListInterface;
use BWD\Stacks\ListCollection;
use BWD\Stacks\Result\ResultCollection;

class SimpleSequence implements SequenceInterface {

  protected $sequence = [];
  /**
   * @var ContentListInterface[]
   */
  protected $hash_store = [];

  /**
   * @TODO switch to private on php7.1
   */
  CONST ALL = 'all';

  CONST HASH_SEPARATOR = ':::';

  /**
   * Add a list
   *
   * @param \BWD\Stacks\ContentList\ContentListInterface $list
   * @param int $number
   *   If Null then it will use all from this list
   *   Future uses of this object in addList will be ignored
   *   But previous uses will be used
   *
   * @return $this
   */
  public function addList(ContentListInterface $list, int $number = NULL) {
    // Get the has for this list item
    $hash = spl_object_hash($list);

    // Store the object.  This can be reused in different sequences
    if (empty($this->hash_store[$hash])) {
      $this->hash_store[$hash] = $list;
    }

    // Create a new key per row for easier parsing later
    $i = count($this->sequence);
    $hash_key = $hash . self::HASH_SEPARATOR . $i;
    if (NULL === $number) {
      $this->sequence[$hash_key] = self::ALL;
    } else {
      $this->sequence[$hash_key] = $number;
    }

    return $this;
  }

  /**
   * Get the object for the sequence
   */
  public function getObjectFromHash($hash) {
    list($obj_hash) = explode(self::HASH_SEPARATOR, $hash);
    return $this->hash_store[$obj_hash] ?? NULL;
  }


  /**
   * Build a Result Collection
   *
   * @param \BWD\Stacks\Result\ResultCollection $result_collection
   * @return \BWD\Stacks\Result\ResultCollection
   */
  public function buildResultCollection(ResultCollection $result_collection) : ResultCollection {
    $return_collection = clone $result_collection;

    $response = [];

    // Build out a array of ids from each list then splice them up
    /** @var ContentListInterface $list */
    foreach ($this->hash_store as $hash => $list) {
      $tmp_result = $list->list($return_collection);
      $tmp_result->setReturnLoadedResults(FALSE);
      $response[$hash] = $tmp_result;
    }

    // track where we are with each object
    $hash_tracker = [];

    // Look through sequence and build lists
    foreach ($this->sequence as $obj_hash => $number) {
      list($obj_hash) = explode(self::HASH_SEPARATOR, $obj_hash);

      /** @var ResultCollection $collection */
      $collection = $response[$obj_hash];

      if (!isset($hash_tracker[$obj_hash])) {
        $hash_tracker[$obj_hash] = 0;
      }

      if (count($collection) === $hash_tracker[$obj_hash]) {
        continue;
      }

      if ($number === self::ALL) {
        // If all then get the rest of the items
        $number = count($collection) - $hash_tracker[$obj_hash];
      }

      $end = $hash_tracker[$obj_hash] + $number;

      // Use either end or the count of collection
      if ($end > count($collection)) {
        $end = count($collection);
      }

      // Add the result items to the return collection
      for ($i = $hash_tracker[$obj_hash];  $i < $end; $i++) {
        $return_collection->addResultItem($collection[$i]);
      }

      $hash_tracker[$obj_hash] = $end;
    }

    return $return_collection;
  }
}