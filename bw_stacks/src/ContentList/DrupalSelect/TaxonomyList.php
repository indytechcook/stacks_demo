<?php

namespace BWStacks\ContentList\DrupalSelect;


use BWD\Stacks\Result\ResultCollection;
use BWD\Stacks\SourceableTrait;
use BWD\Stacks\StacksLoggerTrait;
use BWStacks\StacksConfig;

class TaxonomyList implements DrupalSelectInterface {
  use SourceableTrait;
  use DrupalSelectAlterable;
  use StacksLoggerTrait;

  public function buildQuery(): \SelectQuery {
    $query = db_select('taxonomy_term_data', 't', ['target' => 'slave']);
    $query->fields('t', ['tid']);
    $query->distinct();

    $this->applyAlters($query);

    return $query;
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
    $query = $this->buildQuery();

    $builder = new DrupalSelectResultCollectionBuilder($query);
    $builder->setResultCollection($result);
    $builder->setSource($this->source());
    $builder->setType(StacksConfig::TERM_RESULT_TYPE);
    $builder->setLogger($this->getLogger());
    $builded_list = $builder->build();
    $this->getLogger()->debug("Stacks Taxonomy List " . __CLASS__, [
      'taxonomy_tids' => $builded_list->getIds(),
    ]);

    return $builded_list;
  }
}
