<?php

namespace BWStacks\ContentList\DrupalSelect;

use BWD\Stacks\Result\ResultCollection;
use BWD\Stacks\Result\ResultFactoryRegistry;
use BWD\Stacks\SourceableTrait;
use BWD\Stacks\StacksLoggerTrait;
use BWStacks\StacksConfig;
use SelectQuery;


class NodeList implements DrupalSelectInterface {
  use SourceableTrait;
  use DrupalSelectAlterable;
  use StacksLoggerTrait;

  private $result_type = StacksConfig::NODE_RESULT_TYPE;

  /**
   * Run the actions to get the results of the list
   *
   */
  public function list(ResultCollection $result) : ResultCollection {
    $query = $this->buildQuery();

    $builder = new DrupalSelectResultCollectionBuilder($query);
    $builder->setResultCollection($result);
    $builder->setSource($this->source());
    $builder->setType($this->getResultType());
    $builder->setLogger($this->getLogger());
    $list_builded = $builder->build();
    $this->getLogger()->debug("Stacks Node List " . __CLASS__, [
      'resultIds' => $list_builded->getIds(),
    ]);

    return $builder->build();
  }

  /**
   * Build the query
   *
   * @return \SelectQuery
   */
  public function buildQuery(): SelectQuery {
    // @TODO ideally you would pass in a querybuilder class...
    $query = db_select('node', 'n', ['target' => 'slave']);
    $query->fields('n', ['nid']);
    $query->distinct();

    $this->applyAlters($query);

    return $query;
  }

  /**
   * @return string
   */
  public function getResultType(): string {
    return $this->result_type;
  }

  /**
   * @param string $result_type
   */
  public function setResultType(string $result_type) {
    $this->result_type = $result_type;
  }
}
