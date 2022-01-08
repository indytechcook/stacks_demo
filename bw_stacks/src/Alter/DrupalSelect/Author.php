<?php

namespace BWStacks\Alter\DrupalSelect;


class Author implements DrupalSelectAlterInterface {
  /**
   * @var \BWUser\User
   */
  private $user;

  /**
   * Author constructor.
   * @param $user
   */
  public function __construct($user) { $this->user = $user; }


  /**
   * @param \SelectQuery $query
   */
  public function alter(\SelectQuery $query) {
    $query->condition('n.uid', $this->user->getId());
  }
}

