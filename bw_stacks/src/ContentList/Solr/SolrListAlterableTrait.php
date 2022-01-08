<?php

namespace BWStacks\ContentList\Solr;


use BWConfig\SolrManager;
use BWStacks\Alter\Solr\SolrAlterInterface;

/**
 * Interface SolrAlterableInterface
 * @package BWStacks\ContentList\Solr
 *
 * Provides an interface for SolrAlterableInterface
 */
trait SolrListAlterableTrait {

  /**
   * @var SolrAlterInterface[]
   */
  protected $alters = [];


  /**
   * Apply alters to a query
   *
   * @param SolrManager $query
   * @return mixed
   */
  public function applyAlters(SolrManager $solr) {
    foreach ($this->alters as $alter) {
      $alter->alter($solr);
    }
  }


  /**
   * Add a Alter to the query
   *
   * @param \BWStacks\Alter\Solr\SolrAlterInterface $alter
   * @return mixed
   */
  public function addAlter(SolrAlterInterface $alter) {
    $this->alters[] = $alter;
  }
}