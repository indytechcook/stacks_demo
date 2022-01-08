<?php

namespace BWD\Stacks\Alter\DrupalSelect;

/**
 * @deprecated
 */
class Location implements DrupalSelectAlterInterface {

  /**
   * @var array
   */
  private $location_id;

  /**
   * Location constructor.
   * @param $location_id
   */
  public function __construct(array $location_id) {
    $this->location_id = $location_id;
  }

  public function alter(\SelectQuery $query) {
    $alias = $query->leftJoin('field_data_field_job_location_ref', 'l', 'n.nid = l.entity_id');
    $query->condition("$alias.field_job_location_ref_target_id", $this->location_id, 'IN');
  }
}