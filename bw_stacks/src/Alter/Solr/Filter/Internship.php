<?php

namespace BWStacks\Alter\Solr\Filter;


use BWConfig\SolrManager;
use BWStacks\Alter\Solr\SolrAlterInterface;


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

  public function alter(SolrManager $solr) {
    if ($this->internship) {
      $solr->addToFq(['bs_field_job_internship' => 'true']);
    }
    else {
      $solr->addToFq(['bs_field_job_internship' => 'false']);
    }
  }
}