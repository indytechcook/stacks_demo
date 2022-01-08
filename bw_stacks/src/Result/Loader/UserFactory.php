<?php

namespace BWStacks\Result\Loader;

use BWD\Stacks\Result\Loader\ResultFactoryInterface;
use BWD\Stacks\Result\ResultItem;
use BWUser\User;
use BWUser\UserFactory as UF;

/**
 * Class UserFactory.
 *
 * @package BWStacks\Result\Loader
 */
class UserFactory implements ResultFactoryInterface {

  /**
   * Load item.
   *
   * @param \BWD\Stacks\Result\ResultItem $item
   *
   * @return User
   */
  public function load(ResultItem $item): User {
    return UF::getUser($item->getId());
  }

}
