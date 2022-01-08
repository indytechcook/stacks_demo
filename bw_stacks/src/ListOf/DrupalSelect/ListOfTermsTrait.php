<?php

namespace BWStacks\ListOf\DrupalSelect;


use BWStacks\Alter\DrupalSelect\Taxonomy\Vocab;
use BWStacks\ContentList\DrupalSelect\TaxonomyList;

trait ListOfTermsTrait {
  /**
   * Build a default list for node
   *
   * @param string $vocab
   * @param string $source
   * @return \BWStacks\ContentList\DrupalSelect\TaxonomyList
   */
  private function baseList(string $vocab, string $source) : TaxonomyList {
    $list = new TaxonomyList($source);
    $list->setSource($source);
    $list->addAlter(new Vocab($vocab));
    if (method_exists($this, "getLogger")) {
      $list->setLogger($this->getLogger());
    }

    return $list;
  }
}
