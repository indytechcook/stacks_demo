<?php

namespace BWStacks\ContentList\Solr;


use BWConfig\SolrManager;
use BWD\Stacks\Result\ResultCollection;
use BWD\Stacks\Result\ResultItem;

class SolrListResultCollectionBuilder {

  /**
   * @var ResultCollection
   */
  private $result;

  /**
   * @var string
   */
  private $source;

  /**
   * @var string
   */
  private $type;

  /**
   * @var SolrManager
   */
  private $solr;

  /**
   * SolrListResultCollectionBuilder constructor.
   *
   * @param SolrManager $solr
   */
  public function __construct(SolrManager $solr) {
    $this->solr = $solr;
  }

  public function build() {
    $ids = $this->solr->executeQuery(NULL);

    $return = clone $this->result;
    foreach ($ids as $id) {
      $return->addResultItem(new ResultItem($id, $this->source, $this->type));
    }

    return $return;
  }

  /**
   * @param ResultCollection $result
   */
  public function setResultCollection(ResultCollection $result) {
    $this->result = $result;
  }

  /**
   * @param string $source
   */
  public function setSource(string $source) {
    $this->source = $source;
  }

  /**
   * @param string $type
   */
  public function setType(string $type) {
    $this->type = $type;
  }


}
