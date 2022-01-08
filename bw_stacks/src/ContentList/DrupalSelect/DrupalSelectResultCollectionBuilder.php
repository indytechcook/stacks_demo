<?php

namespace BWStacks\ContentList\DrupalSelect;

use BWD\Stacks\Result\ResultCollection;
use BWD\Stacks\Result\ResultItem;
use BWD\Stacks\StacksLoggerTrait;
use SelectQuery;

class DrupalSelectResultCollectionBuilder {
  use StacksLoggerTrait;

  /**
   * @var ResultCollection
   */
  private $result;

  /**
   * @var string
   */
  private $source;

  /**
   * @var string
   */
  private $type;
  /**
   * @var \SelectQuery
   */
  private $query;

  /**
   * ResultCollectionBuilder constructor.
   * @param \SelectQuery $query
   */
  public function __construct(SelectQuery $query) {
    $this->query = $query;
  }

  public function build() {
    $this->getLogger()->debug("Stacks Drupal Query", [
      'query' => $this->query->__toString(),
      'parameters' => $this->query->arguments(),
    ]);
    $ids = $this->query->execute()->fetchCol();

    $return = clone $this->result;
    foreach ($ids as $id) {
      $return->addResultItem(new ResultItem($id, $this->source, $this->type));
    }

    return $return;
  }

  /**
   * @param string $source
   */
  public function setSource(string $source) {
    $this->source = $source;
  }

  /**
   * @param string $type
   */
  public function setType(string $type) {
    $this->type = $type;
  }

  /**
   * @param \BWD\Stacks\Result\ResultCollection $result
   */
  public function setResultCollection(ResultCollection $result) {
    $this->result = $result;
  }

}
