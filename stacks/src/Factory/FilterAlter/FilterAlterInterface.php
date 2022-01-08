<?php

namespace BWD\Stacks\Factory\FilterAlter;

/**
 * @deprecated
 */
interface FilterAlterInterface {
  public function alterFilters(array $filters): array;
}