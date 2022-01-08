<?php

namespace BWStacks\ContentList\DrupalSelect;

use BWD\Stacks\Result\ResultCollection;
use BWD\Stacks\Result\ResultFactoryRegistry;
use BWD\Stacks\SourceableTrait;
use BWStacks\StacksConfig;
use SelectQuery;

/**
 * Class UserList.
 *
 * @package BWStacks\ContentList\DrupalSelect
 */
class UserList implements DrupalSelectInterface {
  use SourceableTrait;
  use DrupalSelectAlterable;

  private $result_type = StacksConfig::USER_RESULT_TYPE;

  /**
   * Run the actions to get the results of the list.
   */
  public function list(ResultCollection $result) : ResultCollection {
    $query = $this->buildQuery();

    $builder = new DrupalSelectResultCollectionBuilder($query);
    $builder->setResultCollection($result);
    $builder->setSource($this->source());
    $builder->setType($this->getResultType());

    return $builder->build();
  }

  /**
   * Build the query.
   *
   * @return \SelectQuery
   *   Builded Query.
   */
  public function buildQuery(): SelectQuery {
    // @TODO ideally you would pass in a querybuilder class...
    $query = db_select('users', 'u', ['target' => 'slave']);
    $query->fields('u', ['uid']);
    $query->distinct();

    $this->applyAlters($query);

    return $query;
  }

  /**
   * Get result type.
   *
   * @return string
   *   Result type.
   */
  public function getResultType(): string {
    return $this->result_type;
  }

  /**
   * Set result type.
   *
   * @param string $result_type
   *   Result type.
   */
  public function setResultType(string $result_type) {
    $this->result_type = $result_type;
  }

}
