<?php

namespace BWD\Stacks\ContentList;

use BWD\Stacks\NewStackInterface;
use BWD\Stacks\Result\ResultAlterableTrait;
use BWD\Stacks\Result\ResultCollection;
use BWD\Stacks\StacksLoggerTrait;

class ContentListCollectionAdapter implements NewStackInterface {
  use ResultAlterableTrait;
  use StacksLoggerTrait;

  /**
   * @var ContentListCollection
   */
  private $list;

  /**
   * ListCollectionAdapter constructor.
   *
   * @param ContentListCollection $list
   */
  public function __construct(ContentListCollection $list) {
    $this->list = $list;
  }

  /**
   * Run the actions to get the results of the list
   *
   * @param \BWD\Stacks\Result\ResultCollection $result
   *   The ResultCollection to add the results.  This should not be modified
   *
   * @return \BWD\Stacks\Result\ResultCollection
   *   This is a new instance of the result collection.
   */
  public function list(ResultCollection $result): ResultCollection {
    $return = clone $result;

    foreach ($this->list as $list) {
      $r = $list->list($return);
      $r->setReturnLoadedResults(FALSE);

      if (count($r) > 0) {
        foreach ($r as $r_item) {
          $return->addResultItem($r_item);
        }
      }
    }

    $this->alter($return);

    return $return;
  }

  /**
   * Get source, type or machine name of the playlist
   *
   * This will be used when tracking how a item as added
   * to a playlist
   *
   * @return string
   */
  public function source(): string {
    return NULL;
  }
}
