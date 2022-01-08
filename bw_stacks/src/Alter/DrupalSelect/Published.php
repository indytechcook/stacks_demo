<?php

namespace BWStacks\Alter\DrupalSelect;



class Published implements DrupalSelectAlterInterface {
  /**
   * @var bool
   */
  private $published;

  /**
   * Published constructor.
   *
   * @param bool $published
   */
  public function __construct(bool $published = TRUE) {
    $this->published = $published;
  }

  public function alter(\SelectQuery $query) {
    $query->condition('n.status', $this->published);
  }
}