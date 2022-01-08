<?php

namespace BWD\Stacks;

use BWMonolog\BWDLogger;

/**
 * Trait StacksLoggerTrait.
 *
 * @package BWD\Stacks
 */
trait StacksLoggerTrait {

  /**
   * Logger.
   *
   * @var \BWMonolog\BWDLogger
   */
  private $logger;

  /**
   * Get logger.
   *
   * @return \BWMonolog\BWDLogger
   *   Logger.
   */
  public function getLogger(): BWDLogger {
    if (!$this->logger) {
      // @TODO: Remove the dependency on dashboard.
      return \bw_config_get_logger();
    }
    return $this->logger;
  }

  /**
   * Logger.
   *
   * @param \Monolog\Logger $logger
   *   Logger
   */
  public function setLogger(BWDLogger $logger) {
    $this->logger = $logger;
  }

}
