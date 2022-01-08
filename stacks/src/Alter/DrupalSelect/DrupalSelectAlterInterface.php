<?php

namespace BWD\Stacks\Alter\DrupalSelect;

/**
 * @deprecated
 */

interface DrupalSelectAlterInterface {
  public function alter(\SelectQuery $query);
}