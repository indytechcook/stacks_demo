<?php

namespace BWStacks\ContentList\Solr;


use BWConfig\SolrManager;
use BWD\Stacks\ContentList\ContentListInterface;
use BWStacks\Alter\Solr\SolrAlterInterface;
use DrupalApacheSolrServiceInterface;

interface SolrListInterface extends ContentListInterface {
  /**
   * Build the query
   *
   * @param \DrupalApacheSolrServiceInterface $solr
   * @return \BWConfig\SolrManager
   */
  public function buildQuery(DrupalApacheSolrServiceInterface $solr) : SolrManager;

  /**
   * Add a Alter to the query
   *
   * @param \BWStacks\Alter\Solr\SolrAlterInterface $alter
   * @return mixed
   */
  public function addAlter(SolrAlterInterface $alter);

  /**
   * Apply alters to a query
   *
   * @param \BWConfig\SolrManager $solr
   * @return
   */
  public function applyAlters(SolrManager $solr);
}