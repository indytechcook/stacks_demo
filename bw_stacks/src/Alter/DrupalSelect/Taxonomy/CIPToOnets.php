<?php

namespace BWStacks\Alter\DrupalSelect\Taxonomy;


use BWStacks\Alter\DrupalSelect\DrupalSelectAlterInterface;

class CIPToOnets implements DrupalSelectAlterInterface {

  /**
   * @var int[]
   */
  private $cip_ids;

  /**
   * CIPToOnets constructor.
   * @param \int[] $cip_ids
   */
  public function __construct(array $cip_ids) {
    $this->cip_ids = $cip_ids;
  }


  /**
   * @param \SelectQuery $query
   */
  public function alter(\SelectQuery $query) {
    $alias = $query->join('field_data_field_taxonomy_onet_ref', 'ot', 't.tid = ot.field_taxonomy_onet_ref_target_id');
    $query->condition("$alias.entity_id", $this->cip_ids, 'IN');
  }
}
