<?php

namespace BWStacks\Alter\DrupalSelect;


class OrderByNid implements DrupalSelectAlterInterface {
  /**
   * @var string
   */
  private $dir = 'DESC';

  const ASC = 'ASC';
  const DESC = 'DESC';

  /**
   * OrderByNid constructor.
   * @param string $dir
   */
  public function __construct($dir) {
    $this->dir = $dir;
  }


  /**
   * @param \SelectQuery $query
   */
  public function alter(\SelectQuery $query) {
    $query->orderBy('n.nid', $this->dir);
  }
}