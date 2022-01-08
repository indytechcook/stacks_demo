<?php

namespace BWStacks\Result\Loader;


use BWConfig\ConfigNodeFactory;
use BWD\Stacks\Result\Loader\ResultFactoryInterface;
use BWD\Stacks\Result\ResultItem;

class NodeFactory implements ResultFactoryInterface {

  public function load(ResultItem $item) {
    return ConfigNodeFactory::createByNid($item->getId());
  }
}