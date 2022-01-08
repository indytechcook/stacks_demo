<?php

namespace BWD\Stacks\Alter;

use BWD\Stacks\Alter\Results\ResultAlterInterface;

/**
 * Class ResultAlterable
 * @package BWD\Stacks\Alter
 *
 * @deprecated
 */
trait ResultAlterable  {
  /**
   * @var ResultAlterInterface[];
   */
  protected $resultAlters = [];

  /**
   * Add a result Alter.
   *
   * @param \BWD\Stacks\Alter\Results\ResultAlterInterface $alter
   */
  public function addResultAlter(ResultAlterInterface $alter) {
    $this->resultAlters[] = $alter;
  }
}