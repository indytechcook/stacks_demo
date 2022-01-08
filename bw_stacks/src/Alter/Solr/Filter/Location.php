<?php

namespace BWStacks\Alter\Solr\Filter;


use BWConfig\SolrManager;
use BWStacks\Alter\Solr\SolrAlterInterface;
use BWJob\LocationTaxonomy;

class Location implements SolrAlterInterface {

  /**
   * @var array
   */
  private $location_tids;

  /**
   * Location constructor.
   * @param array $location_tids
   */
  public function __construct(array $location_tids = []) {
    $this->location_tids = $location_tids;
  }

  public function alter(SolrManager $solr) {
    if ($this->location_tids) {
      $locations = LocationTaxonomy::expandLocations($this->location_tids);
      // limit the number of location to 1000
      $limit = variable_get('bw_location_limit', 1000);
      if (count($locations) > $limit) {
        $locations = array_slice($locations, 0, $limit);
      }

      $result['sm_field_job_location_ref'] = implode(' OR ', array_map(function ($elem) {
        return '"' . 'taxonomy_term:' . $elem . '"';
      }, $locations));

      $solr->addToFq($result);
    }
  }
}