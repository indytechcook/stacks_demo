<?php

namespace BWStacks\ListOf\DrupalSelect;

use BWStacks\ContentList\DrupalSelect\UserList;
use BWStacks\StacksConfig;
use BWUser\Stacks\Alter\DrupalSelect\Published;

/**
 * Class ListOfUsersTrait.
 *
 * @package BWStacks\ListOf\DrupalSelect
 */
trait ListOfUsersTrait {

  /**
   * Build a list for User.
   *
   * @param string $source
   *   Source.
   *
   * @param string $result_type_alias
   *   The alias to use for the result type.
   *
   * @return \BWStacks\ContentList\DrupalSelect\UserList
   *   User List.
   */
  private function baseList(string $source, string $result_type_alias = StacksConfig::USER_RESULT_TYPE) : UserList {
    $list = new UserList();
    $list->setSource($source);
    $list->setResultType($result_type_alias);
    $list->addAlter(new Published());

    return $list;
  }

}
