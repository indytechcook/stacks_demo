<?php

namespace BWStacks\Alter\DrupalSelect;


interface DrupalSelectAlterInterface {
  public function alter(\SelectQuery $query);
}