<?php

namespace BWD\Stacks\Alter\DrupalSelect;

/**
 * @deprecated
 */
class Internship implements DrupalSelectAlterInterface {
  /**
   * @var bool
   */
  private $internship;

  /**
   * Internship constructor.
   * @param bool $internship
   */
  public function __construct(bool $internship) {
    $this->internship = $internship;
  }

  public function alter(\SelectQuery $query) {
    $alias = $query->leftJoin('field_data_field_job_internship', 'i', 'n.nid = i.entity_id');
    $query->condition("$alias.field_job_internship_value", $this->internship);
  }
}