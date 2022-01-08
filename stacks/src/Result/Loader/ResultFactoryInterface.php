<?php

namespace BWD\Stacks\Result\Loader;


use BWD\Stacks\Result\ResultItem;

interface ResultFactoryInterface {
  /**
   * Load the object
   *
   * @param \BWD\Stacks\Result\ResultItem $item
   * @return mixed
   */
  public function load(ResultItem $item);
}