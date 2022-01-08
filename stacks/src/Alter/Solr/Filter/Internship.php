<?php

namespace BWD\Stacks\Alter\Solr\Filter;


use BWConfig\SolrManager;
use BWD\Stacks\Alter\Solr\SolrAlterInterface;

/**
 * @deprecated
 */
class Internship implements SolrAlterInterface {

  /**
   * @var bool
   */
  private $internship;

  /**
   * Internship constructor.
   * @param bool $internship
   */
  public function __construct(bool $internship = FALSE) {
    $this->internship = $internship;
  }

  public function alterQuery(SolrManager $solr) {
    if ($this->internship) {
      $solr->addToFq(['bs_field_job_internship' => 'true']);
    }
    else {
      $solr->addToFq(['bs_field_job_internship' => 'false']);
    }
  }
}