<?php


namespace BWD\Stacks\Result\Loader;


use BWD\Stacks\Result\ResultItem;

class ResultItemLoader {
  /**
   * @var \BWD\Stacks\Result\Loader\ResultLoaderProvider
   */
  private $provider;

  /**
   * ResultItemLoader constructor.
   * @param \BWD\Stacks\Result\Loader\ResultLoaderProvider $provider
   */
  public function __construct(ResultLoaderProvider $provider) {
    $this->provider = $provider;
  }

  public function load(ResultItem $result) {
    $factory = $this->getFactory($result);

    return $factory->load($result);
  }

  /**
   * Get the factory for loading the object
   *
   * @param \BWD\Stacks\Result\ResultItem $item
   * @return \BWD\Stacks\Result\Loader\ResultFactoryInterface
   * @throws \BWD\Stacks\Result\Loader\ResultFactoryException
   */
  private function getFactory(ResultItem $item) {
    return $this->provider->getResultLoaderFactory($item->getType());
  }
}