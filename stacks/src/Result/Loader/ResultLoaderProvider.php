<?php

namespace BWD\Stacks\Result\Loader;


class ResultLoaderProvider {
  /**
   * @var ResultFactoryInterface[]
   */
  private $registry = [];
  /**
   * @var string[string]
   *
   * Alias of a name to a type.
   */
  private $aliases = [];

  /**
   * Add a result loader
   *
   * @param string $type
   *   What type to use to load.
   *   This matches the type of the ResultItem
   * @var $factory ResultFactoryInterface
   */
  public function addResultFactory(string $type, ResultFactoryInterface $factory) {
    $this->registry[$type] = $factory;
  }

  /**
   * @param string $type
   *   The factory to load
   *
   * @return \BWD\Stacks\Result\Loader\ResultFactoryInterface
   *
   * @throws \BWD\Stacks\Result\Loader\ResultFactoryException
   */
  public function getResultLoaderFactory(string $type) : ResultFactoryInterface {
    // Check for aliases
    if (isset($this->aliases[$type])) {
      $type = $this->aliases[$type];
    }

    if (isset($this->registry[$type])) {
      return $this->registry[$type];
    }

    throw new ResultFactoryException($type . ' Does not exist');
  }

  public function addAlias($alias, $factory_key) {
    $this->aliases[$alias] = $factory_key;
  }

}