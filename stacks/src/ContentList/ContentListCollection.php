<?php

namespace BWD\Stacks\ContentList;

class ContentListCollection implements \Iterator, \ArrayAccess, \Countable {
  /**
   * @var ContentListInterface[]
   */
  private $lists = [];

  /**
   * Return the current element
   * @link http://php.net/manual/en/iterator.current.php
   *
   * @return ContentListInterface
   */
  public function current() {
    return current($this->lists);
  }

  /**
   * Move forward to next element
   * @link http://php.net/manual/en/iterator.next.php
   *
   * @return void Any returned value is ignored.
   */
  public function next() {
    next($this->lists);
  }

  /**
   * Return the key of the current element
   * @link http://php.net/manual/en/iterator.key.php
   *
   * @return mixed scalar on success, or null on failure.
   */
  public function key() {
    return key($this->lists);
  }

  /**
   * Checks if current position is valid
   * @link http://php.net/manual/en/iterator.valid.php
   *
   * @return boolean The return value will be casted to boolean and then evaluated.
   * Returns true on success or false on failure.
   */
  public function valid() {
    return current($this->lists);
  }

  /**
   * Rewind the Iterator to the first element
   * @link http://php.net/manual/en/iterator.rewind.php
   *
   * @return void Any returned value is ignored.
   */
  public function rewind() {
    reset($this->lists);
  }

  /**
   * Whether a offset exists
   * @link http://php.net/manual/en/arrayaccess.offsetexists.php
   *
   * @param mixed $offset
   *   An offset to check for.
   *
   * @return boolean true on success or false on failure.
   *   The return value will be casted to boolean if non-boolean was returned.
   */
  public function offsetExists($offset) {
    return !empty($this->lists[$offset]);
  }

  /**
   * Offset to retrieve
   * @link http://php.net/manual/en/arrayaccess.offsetget.php
   *
   * @param mixed $offset
   *   The offset to retrieve.
   *
   * @return ListInterface
   */
  public function offsetGet($offset) {
    return $this->lists[$offset];
  }

  /**
   * Offset to set
   * @link http://php.net/manual/en/arrayaccess.offsetset.php
   *
   * @param mixed $offset
   *   The offset to assign the value to.
   * @param mixed $value
   *   The value to set.
   *
   * @throws \Exception
   */
  public function offsetSet($offset, $value) {
    if ($value instanceof ListInterface) {
      $this->lists[$offset] = $value;
    }
    else {
      throw new \Exception('Type must be PlaylistInterface');
    }
  }

  /**
   * Offset to unset
   * @link http://php.net/manual/en/arrayaccess.offsetunset.php
   *
   * @param mixed $offset
   *  The offset to unset.
   *
   * @return void
   */
  public function offsetUnset($offset) {
    unset($this->lists[$offset]);
  }

  /**
   * Count elements of an object
   * @link http://php.net/manual/en/countable.count.php
   *
   * @return int
   *   The custom count as an integer.
   */
  public function count() {
    return count($this->lists);
  }

  /**
   * Add a stack to the list of stacks.  The stacks are accessed in order.
   *
   * @param \BWD\Stacks\ListInterface $list
   *
   * @return mixed
   */
  public function addList(ContentListInterface $list) {
    $this->lists[] = $list;
  }

  /**
   * Get all of the stocks
   *
   * @return ContentListInterface[]
   */
  public function getLists(): array {
    return $this->lists;
  }

  /**
   * Set all the stacks.
   *
   * @param ContentListInterface[] $lists
   */
  public function setLists(array $lists) {
    $this->lists = $lists;
  }
}