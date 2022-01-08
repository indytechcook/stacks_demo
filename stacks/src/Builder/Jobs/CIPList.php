<?php


namespace BWD\Stacks\Builder\Jobs;


use BWCip\CipTaxonomy;

/**
 * @deprecated
 */
class CIPList extends JobsList {

  /**
   * @var array
   */
  private $cip_ids;

  /**
   * CIPPlaylist constructor.
   *
   * @param array $cip_ids
   */
  public function __construct(array $cip_ids) {
    $this->cip_ids = $cip_ids;
  }

  /**
   * @param array $filters
   * @return \SelectQuery
   */
  public function buildQuery(array $filters): \SelectQuery {
    $query = parent::buildQuery($filters);

    $onet_ids = CipTaxonomy::getONETIdsByCIPIds($this->cip_ids);

    if ($onet_ids) {
      $alias_o = $query->join('field_data_field_job_onet_ref', 'o', 'n.nid = o.entity_id');
      $query->condition("$alias_o.field_job_onet_ref_target_id", $onet_ids, 'IN');
    }

    return $query;
  }

  /**
   * Get source, type or machine name of the list
   *
   * This will be used when tracking how a item as added
   * to a playlist
   *
   * @return string
   */
  public function source(): string {
    return 'cip';
  }
}