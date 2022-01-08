<?php

namespace BWStacks\Result\Loader;


use BWConfig\ConfigTaxonomyFactory;
use BWD\Stacks\Result\Loader\ResultFactoryInterface;
use BWD\Stacks\Result\ResultItem;

class TaxonomyFactory implements ResultFactoryInterface {

  public function load(ResultItem $item) {
    return ConfigTaxonomyFactory::createByTid($item->getId());
  }
}