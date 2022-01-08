<?php

namespace BWStacks\Alter\DrupalSelect\Taxonomy;


use BWStacks\Alter\DrupalSelect\DrupalSelectAlterInterface;

class OrderByTid implements DrupalSelectAlterInterface {
  /**
   * @var string
   */
  private $dir = 'DESC';

  const ASC = 'ASC';
  const DESC = 'DESC';

  /**
   * @param string $dir
   */
  public function __construct($dir) {
    $this->dir = $dir;
  }

  public function alter(\SelectQuery $query) {
    $query->orderBy('t.tid', $this->dir);
  }
}