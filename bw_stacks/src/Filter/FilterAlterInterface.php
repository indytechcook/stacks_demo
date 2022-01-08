<?php

namespace BWStacks\Filter;

interface FilterAlterInterface {
  public function alterFilters(array $filters): array;
}