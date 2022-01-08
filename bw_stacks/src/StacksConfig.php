<?php

namespace BWStacks;

use BWD\Stacks\Result\Loader\ResultItemLoader;
use BWD\Stacks\Result\Loader\ResultLoaderProvider;

class StacksConfig {

  // The result type constants.
  CONST NODE_RESULT_TYPE = 'node';
  CONST TERM_RESULT_TYPE = 'taxonomy_term';
  CONST USER_RESULT_TYPE = 'user';
  CONST QUERY_LIMIT = 100;

  /**
   * @var ResultLoaderProvider
   */
  private static $result_loader_provider;

  /**
   * @return ResultLoaderProvider
   */
  public static function getResultLoaderProvider(): ResultLoaderProvider {
    return self::$result_loader_provider;
  }

  /**
   * @param ResultLoaderProvider $result_loader_provider
   */
  public static function setResultLoaderProvider(ResultLoaderProvider $result_loader_provider) {
    self::$result_loader_provider = $result_loader_provider;
  }

  /**
   * @return \BWD\Stacks\Result\Loader\ResultItemLoader
   */
  public static function getResultItemLoader() {
    return new ResultItemLoader(StacksConfig::getResultLoaderProvider());
  }

}
