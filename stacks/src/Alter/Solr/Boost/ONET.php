<?php

namespace BWD\Stacks\Alter\Solr\Boost;


use BWConfig\SolrManager;
use BWD\Stacks\Alter\Solr\SolrAlterInterface;

/**
 * @deprecated
 */
class ONET implements SolrAlterInterface {

  /**
   * @var array
   */
  private $positive_tids = [];
  /**
   * @var array
   */
  private $negative_tids = [];


  /**
   * Build out the Solr query necessary
   *
   * @param \BWConfig\SolrManager $solr
   */
  public function alterQuery(SolrManager $solr) {
    // Get user's down-voted job ids.

    if ($this->negative_tids) {
      // Negative_boosts_tids from user object.
      foreach ($this->negative_tids as $tid) {
        $solr->addToBQ($solr->buildBoostQuery('sm_field_job_onet_ref', "*:* -taxonomy_term:{$tid}", 999, FALSE));
      }
    }

    // Get user's up-voted job ids.
    if ($this->positive_tids) {
      // Positive boosts tids from user object.
      foreach ($this->positive_tids as $tid) {
        $solr->addToBQ($solr->buildBoostQuery('sm_field_job_onet_ref', "taxonomy_term:{$tid}", 1.001, FALSE));
      }
    }
  }

  /**
   * @param mixed $positive_tids
   * @return ONET
   */
  public function setPositiveTids($positive_tids) {
    $this->positive_tids = $positive_tids;
    return $this;
  }

  /**
   * @param mixed $negative_tids
   * @return ONET
   */
  public function setNegativeTids($negative_tids) {
    $this->negative_tids = $negative_tids;
    return $this;
  }

}