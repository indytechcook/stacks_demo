<?php

namespace BWStacks\Alter\ResultCollection;

use BWD\Stacks\Result\ResultCollection;

trait MarkResultItemsAlteredTrait {
  public function markResultItemsAltered(ResultCollection $results, array $ids, string $alterer) {
    foreach ($results as $result) {
      if (in_array($result->getId(), $ids)) {
        $result->setAltered(TRUE);
        $result->setAlteredBy($alterer);
      };
    }
  }
}
