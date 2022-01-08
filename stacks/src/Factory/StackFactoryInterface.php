<?php

namespace BWD\Stacks\Factory;


use BWD\Stacks\StackInterface;

/**
 * @deprecated
 */
interface StackFactoryInterface {
  /**
   * Build a Playlist for use in Feed class
   *
   * @param array $filters
   *
   * @param bool $cache
   * @return \BWD\Stacks\StackInterface
   */
  public function build(array $filters = [], bool $cache = FALSE): StackInterface;
}