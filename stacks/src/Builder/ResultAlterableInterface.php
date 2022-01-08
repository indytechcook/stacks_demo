<?php
/**
 * Created by PhpStorm.
 * User: indytechcook
 * Date: 10/18/16
 * Time: 3:32 PM
 */

namespace BWD\Stacks\Builder;


use BWD\Stacks\Alter\Results\ResultAlterInterface;

/**
 * @deprecated
 */
interface ResultAlterableInterface {
  /**
   * Add a result Alter.
   *
   * @param ResultAlterInterface $alter
   */
  public function addResultAlter(ResultAlterInterface $alter);
}