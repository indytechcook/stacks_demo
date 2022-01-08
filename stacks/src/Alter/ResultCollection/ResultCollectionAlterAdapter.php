<?php

namespace BWD\Stacks\Alter\ResultCollection;


use BWD\Stacks\Alter\Results\ResultAlterInterface;
use BWD\Stacks\Result\ResultCollection;
use BWD\Stacks\Result\ResultItem;
use BWD\Stacks\StacksLoggerTrait;

class ResultCollectionAlterAdapter implements ResultCollectionAlterInterface {
  use StacksLoggerTrait;

  /**
   * @var ResultAlterInterface
   */
  private $result_alter;

  /**
   * ResultCollectionAlterAdapter constructor.
   * @param ResultAlterInterface $result_alter
   */
  public function __construct(ResultAlterInterface $result_alter) {
    $this->result_alter = $result_alter;
  }


  /**
   * Alter a Result Collection Object
   *
   * @param \BWD\Stacks\Result\ResultCollection $results
   */
  public function alter(ResultCollection $results) {

    // Build result array in correct format
    // The assumption here is that a all results to alter are the same type
    $results_to_alter = [];
    $type = '';
    /** @var ResultItem $result */
    foreach ($results as $key => $result) {
      $results_to_alter[$result->getId()] = $result->getSource();
      if (!$type) {
        $type = $result->getType();
      }
    }

    if ($this->getLogger()) {
      $this->getLogger()->debug("Stacks Result Collection Alter Adapter " . __CLASS__, [
        'full_results' => $results->getIds(),
        'results_to_alter' => $results_to_alter,
      ]);
    }

    list($results_to_alter, $alters) = $this->result_alter->alter($results_to_alter);

    if ($alters) {
      $last_results = $results->getResultItems();

      // Rebuild result items
      $results->setResultItems([]);
      foreach ($results_to_alter as $id => $source) {
        $result_item = $this->findResultItem($last_results, implode(ResultItem::SEPERATOR, [$type, $id]));
        if (!$result_item) {
          $result_item = new ResultItem($id, $source, $type);
        }

        if (in_array($id, $alters)) {
          // this is an altered result, mark it!
          $result_item->setAltered(TRUE);
          $result_item->setAlteredBy($this->getResultAltererName());
        }
        $results->addResultItem($result_item);
      }
    }
  }

  private function getResultAltererName() {
    $name = get_class($this->result_alter);
    $name = strtolower(substr($name, strrpos($name, '\\') + 1));

    return $name;
  }

  private function findResultItem($results, $id) {
    /** @var ResultItem $result */
    foreach ($results as $result) {
      if ($result->uniqueId() == $id) {
        return $result;
      }
    }

    return NULL;
  }
}
