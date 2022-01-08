<?php

namespace BWD\Stacks\ListOf;


use BWD\Stacks\ContentList\ContentListInterface;

interface ListOfInterface {
  /**
   * Get a List Object to build
   *
   * @return \BWD\Stacks\ContentList\ContentListInterface
   */
  public function getList() : ContentListInterface;
}