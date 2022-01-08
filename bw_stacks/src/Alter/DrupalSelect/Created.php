<?php

namespace BWStacks\Alter\DrupalSelect;


class Created implements DrupalSelectAlterInterface {
  /**
   * @var \DateTimeImmutable
   */
  private $date;

  /**
   * Created constructor.
   *
   * @param $date
   */
  public function __construct(\DateTimeImmutable $date) {
    $this->date = $date;
  }


  public function alter(\SelectQuery $query) {
    $query->condition('n.created', $this->date->getTimestamp(), '>');
  }
}