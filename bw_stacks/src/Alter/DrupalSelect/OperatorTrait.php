<?php
/**
 * Created by PhpStorm.
 * User: indytechcook
 * Date: 6/1/17
 * Time: 10:29 AM
 */

namespace BWStacks\Alter\DrupalSelect;


trait OperatorTrait {
  /**
   * @var string
   */
  private $operator;

  /**
   * @return string
   */
  public function getOperator(): string {
    return $this->operator;
  }

  /**
   * @param string $operator
   */
  public function setOperator(string $operator) {
    $this->operator = $operator;
  }
}