<?php

namespace BWStacks\Alter\DrupalSelect;


class JobOnets implements DrupalSelectAlterInterface {

  /**
   * @var int[]
   */
  private $onet_ids;

  /**
   * JobOnets constructor.
   *
   * @param array $onet_ids
   *   Onet ids.
   */
  public function __construct(array $onet_ids) {
    $this->onet_ids = $onet_ids;
  }


  /**
   * @param \SelectQuery $query
   */
  public function alter(\SelectQuery $query) {
    if (count($this->onet_ids)) {
      $alias_o = $query->leftJoin('field_data_field_job_onet_ref', 'o', 'n.nid = o.entity_id');
      $query->condition("$alias_o.field_job_onet_ref_target_id", $this->onet_ids, 'IN');
    }
  }
}
