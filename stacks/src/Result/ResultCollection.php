<?php

namespace BWD\Stacks\Result;


use ArrayAccess;
use BWD\Stacks\Alter\Results\Moveable;
use BWD\Stacks\Result\Loader\ResultFactoryException;
use BWD\Stacks\Result\Loader\ResultItemLoader;
use BWD\Stacks\Result\Loader\ResultLoaderProvider;
use Countable;
use Iterator;
use Serializable;
use Traversable;

class ResultCollection implements Iterator, ArrayAccess, Countable, Serializable {
  use Moveable {
    Moveable::moveToFront as arrayMoveToFront;
    Moveable::moveToBack as arrayMoveToBack;
  }

  /**
   * @var ResultItem[]
   */
  private $result_items = [];

  /**
   * @var \BWD\Stacks\Result\Loader\ResultItemLoader
   */
  private $result_loader;
  /**
   * @var bool
   */
  private $return_loaded_results = FALSE;
  /**
   * @var int
   */
  private $limit;
  /**
   * Track ids added to result items.
   *
   * @var array
   */
  private $unique_items_ids = [];

  /**
   * ResultCollection constructor.
   * @param ResultItemLoader $result_loader
   */
  public function __construct(ResultItemLoader $result_loader) {
    $this->result_loader = $result_loader;
  }

  /**
   * Get the Object
   *
   * @param \BWD\Stacks\Result\ResultItem $item
   * @return mixed
   * @throws \BWD\Stacks\Result\Loader\ResultFactoryException
   */
  private function load(ResultItem $item) {
    return $this->result_loader->load($item);
  }

  /**
   * Generate a unique id for the item.
   *
   * @param \BWD\Stacks\Result\ResultItem $item
   * @return string
   */
  private function generateId(ResultItem $item) {
    return $item->uniqueId();
  }

  /**
   * Add a unique result item.
   *
   * @param \BWD\Stacks\Result\ResultItem $item
   *   Result item object.
   *
   * @return \BWD\Stacks\Result\ResultCollection $this
   *   ResultCollection object.
   */
  public function addResultItem(ResultItem $item) {
    $uniqueId = $this->generateId($item);
    if (!isset($this->unique_items_ids[$uniqueId])) {
      $this->unique_items_ids[$uniqueId] = $uniqueId;
      $this->result_items[] = $item;
    }

    return $this;
  }

  /**
   * Get all ids
   *
   * These are the ids from the ResultItem.
   * This is useful if you know all the ResultItems are the same type
   *
   * @return int[]
   */
  public function getIds() {
    $ids = [];
    // get the current pointer in case we have changed it
    $old_pos = key($this->result_items);

    reset($this->result_items);
    foreach ($this->result_items as $item) {
      $ids[] = $item->getId();
    }

    // reset the pointer
    while (key($this->result_items) !== $old_pos) next($this->result_items);

    return $ids;
  }

  /**
   * Get the Generated Ids
   *
   * @return string[]
   */
  public function getUniqueIds() : array {
    $ids = [];
    // get the current cursor in case we have changed it
    $old_pos = key($this->result_items);

    reset($this->result_items);
    foreach ($this->result_items as $item) {
      $ids[] = $this->generateId($item);
    }

    reset($this->result_items);

    // reset the cursor
    while (key($this->result_items) !== $old_pos) next($this->result_items);

    return $ids;
  }

  /**
   * Set an array of result items
   *
   * @param ResultItem[] $results
   */
  public function setResultItems(array $results) {
    $this->result_items = $results;
    $this->results = [];
  }

  /**
   * @return \BWD\Stacks\Result\ResultItem[]
   */
  public function getResultItems() {
    return $this->result_items;
  }


  /**
   * Count elements of an object
   * @link http://php.net/manual/en/countable.count.php
   * @return int The custom count as an integer.
   * </p>
   * <p>
   * The return value is cast to an integer.
   * @since 5.1.0
   */
  public function count() {
    return count($this->result_items);
  }

  /**
   * Return the current element
   * @link http://php.net/manual/en/iterator.current.php
   * @return mixed Can return any type.
   * @since 5.0.0
   */
  public function current() {
    if ($this->return_loaded_results) {
      return $this->load(current($this->result_items));
    }

    return current($this->result_items);
  }

  /**
   * Move forward to next element
   * @link http://php.net/manual/en/iterator.next.php
   * @return void Any returned value is ignored.
   * @since 5.0.0
   */
  public function next() {
    next($this->result_items);
  }

  /**
   * Return the key of the current element
   * @link http://php.net/manual/en/iterator.key.php
   * @return mixed scalar on success, or null on failure.
   * @since 5.0.0
   */
  public function key() {
    key($this->result_items);
  }

  /**
   * Checks if current position is valid
   * @link http://php.net/manual/en/iterator.valid.php
   * @return boolean The return value will be casted to boolean and then evaluated.
   * Returns true on success or false on failure.
   * @since 5.0.0
   */
  public function valid() {
    return key($this->result_items) !== NULL;
  }

  /**
   * Rewind the Iterator to the first element
   * @link http://php.net/manual/en/iterator.rewind.php
   * @return void Any returned value is ignored.
   * @since 5.0.0
   */
  public function rewind() {
    reset($this->result_items);
  }

  /**
   * Whether a offset exists
   * @link http://php.net/manual/en/arrayaccess.offsetexists.php
   * @param mixed $offset <p>
   * An offset to check for.
   * </p>
   * @return boolean true on success or false on failure.
   * </p>
   * <p>
   * The return value will be casted to boolean if non-boolean was returned.
   * @since 5.0.0
   */
  public function offsetExists($offset) {
    return isset($this->result_items[$offset]);
  }

  /**
   * Offset to retrieve
   * @link http://php.net/manual/en/arrayaccess.offsetget.php
   * @param mixed $offset <p>
   * The offset to retrieve.
   * </p>
   * @return mixed Can return all value types.
   * @since 5.0.0
   */
  public function offsetGet($offset) {
    try {
      if ($this->return_loaded_results) {
        return $this->load($this->result_items[$offset]);
      }

      return $this->result_items[$offset];
    } catch (ResultFactoryException $e) {
      return NULL;
    }
  }

  /**
   * Offset to set
   * @link http://php.net/manual/en/arrayaccess.offsetset.php
   * @param mixed $offset <p>
   * The offset to assign the value to.
   * </p>
   * @param mixed $value <p>
   * The value to set.
   * </p>
   * @return void
   * @since 5.0.0
   */
  public function offsetSet($offset, $value) {
    if ($value instanceof ResultItem) {
      $this->result_items[$offset] = $value;
    }

    throw new ResultFactoryException('Must be type of ResultItem');
  }

  /**
   * Offset to unset
   * @link http://php.net/manual/en/arrayaccess.offsetunset.php
   * @param mixed $offset <p>
   * The offset to unset.
   * </p>
   * @return void
   * @since 5.0.0
   */
  public function offsetUnset($offset) {
    unset($this->result_items[$offset]);
  }


  /**
   * String representation of object
   * @link http://php.net/manual/en/serializable.serialize.php
   * @return string the string representation of the object or null
   * @since 5.1.0
   */
  public function serialize() {
    $caches = [];

    // get the current cursor in case we have changed it
    $old_pos = key($this->result_items);

    reset($this->result_items);
    foreach ($this->result_items as $item) {
      $caches[] = $item->asArray();
    }

    reset($this->result_items);

    // reset the cursor
    while (key($this->result_items) !== $old_pos) next($this->result_items);

    return serialize($caches);
  }

  /**
   * Constructs the object
   * @link http://php.net/manual/en/serializable.unserialize.php
   * @param string $serialized <p>
   * The string representation of the object.
   * </p>
   * @return void
   * @since 5.1.0
   */
  public function unserialize($serialized) {
    $items = unserialize($serialized, ['allowed_classes' => [ResultItem::class]]);

    foreach ($items as $item) {
      $this->addResultItem(ResultItem::buildFromArray($item));
    }
  }

  /**
   * Reduce the size of the array by limit
   *
   * @param int $start
   * @param int $end
   */
  public function range(int $start, int $end) {
    $this->result_items = array_slice($this->result_items, $start, $end);
    $this->results = [];
  }

  /**
   * Set a limit on the number of items
   *
   * @param int $limit
   */
  public function limit(int $limit) {
    $this->limit = $limit;
    $this->range(0, $limit);
  }

  /**
   * @param bool $return_loaded_results
   */
  public function setReturnLoadedResults(bool $return_loaded_results) {
    $this->return_loaded_results = $return_loaded_results;
  }

  /**
   * @param ResultItem[] $items
   * @param bool $push_to_front
   */
  public function addResultItems(array $items, bool $push_to_front = FALSE) {
    if ($push_to_front) {
      $this->result_items = $items + $this->result_items;
    } else {
      $this->result_items = $this->result_items + $items;
    }
  }

  /**
   * Move given by id elements to front
   * @param $ids
   */
  public function moveToFront(array $ids) {
    $result_items = array_combine(
      array_map(function(ResultItem $item) { return $item->getId(); }, $this->result_items),
      $this->result_items
    );

    $this->result_items = array_values($this->arrayMoveToFront($result_items, $ids));
  }

  /**
   * Move given by id elements to back
   * @param $ids
   */
  public function moveToBack(array $ids) {
    $result_items = array_combine(
      array_map(function(ResultItem $item) { return $item->getId(); }, $this->result_items),
      $this->result_items
    );

    $this->result_items = array_values($this->arrayMoveToBack($result_items, $ids));
  }

  /**
   * Remove results by id
   * @param $ids
   */
  public function remove(array $ids) {
    $result_items = array_combine(
      array_map(function(ResultItem $item) { return $item->getId(); }, $this->result_items),
      $this->result_items
    );
    $array_to_remove = array_combine($ids, $ids);

    $this->result_items = array_values(array_diff_key($result_items, $array_to_remove));
  }
}
