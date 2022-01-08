<?php

namespace BWStacks\ContentList\Solr;

use BWConfig\SolrManager;
use BWD\Stacks\Result\ResultCollection;
use BWD\Stacks\SourceableTrait;
use BWD\Stacks\StacksLoggerTrait;
use BWStacks\StacksConfig;
use DrupalApacheSolrServiceInterface;

class SolrList implements SolrListInterface {
  use SourceableTrait;
  use SolrListAlterableTrait;
  use StacksLoggerTrait;

  /**
   * @var string
   */
  private $type = StacksConfig::NODE_RESULT_TYPE;

  /**
   * @var \DrupalApacheSolrServiceInterface
   */
  private $solr;

  /**
   * NodeList constructor.
   * @param \DrupalApacheSolrServiceInterface $solr
   */
  public function __construct(DrupalApacheSolrServiceInterface $solr = NULL) {
    $this->solr = $solr ?? apachesolr_get_solr();
  }


  /**
   * Run the actions to get the results of the list
   *
   * @param \BWD\Stacks\Result\ResultCollection $result
   *   The ResultCollection to add the results.  This should not be modified
   *
   * @return \BWD\Stacks\Result\ResultCollection
   *   This is a new instance of the result collection.
   */
  public function list(ResultCollection $result): ResultCollection {
    $solr = $this->buildQuery($this->solr);

    $builder = new SolrListResultCollectionBuilder($solr);
    $builder->setResultCollection($result);
    $builder->setSource($this->source());
    $builder->setType($this->type);
    $list_builded = $builder->build();

    $this->getLogger()->debug("Stacks Solr List " . __CLASS__, [
      'q' => $solr->getQ(),
      'fq' => $solr->getFq(),
      'bq' => $solr->getBq(),
      'fields' => $solr->getFields(),
      'rows' => $solr->getLimit(),
      'returned_ids' => $list_builded->getIds(),
    ]);

    return $list_builded;
  }

  /**
   * Build the query
   *
   * @return SolrManager
   */
  public function buildQuery(\DrupalApacheSolrServiceInterface $solr): SolrManager {
    $solr_manager = new SolrManager($solr);
    $this->applyAlters($solr_manager);

    return $solr_manager;
  }

  /**
   * @param mixed $solr
   */
  public function setSolr(DrupalApacheSolrServiceInterface $solr) {
    $this->solr = $solr;
  }

  /**
   * @param string $type
   */
  public function setType(string $type) {
    $this->type = $type;
  }
}
