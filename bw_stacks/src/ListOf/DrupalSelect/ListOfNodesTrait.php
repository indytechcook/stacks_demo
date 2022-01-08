<?php

namespace BWStacks\ListOf\DrupalSelect;


use BWStacks\Alter\DrupalSelect\ContentType;
use BWStacks\Alter\DrupalSelect\Published;
use BWStacks\ContentList\DrupalSelect\NodeList;
use BWStacks\StacksConfig;

trait ListOfNodesTrait {
  /**
   * Build a default list for node
   *
   * @param string $type
   *   The content type
   * @param string $source
   * @param string $result_type_alias
   *   The alias to use for the result type
   *
   * @param bool $published
   * @return \BWStacks\ContentList\DrupalSelect\NodeList
   */
  private function baseList(string $type, string $source, $result_type_alias = StacksConfig::NODE_RESULT_TYPE, $published = TRUE): NodeList {
    $list = new NodeList();
    $list->setSource($source);
    $list->setResultType($result_type_alias);
    $list->addAlter(new ContentType($type));
    $list->addAlter(new Published($published));
    if (method_exists($this, "getLogger")) {
      $list->setLogger($this->getLogger());
    }

    return $list;
  }
}
