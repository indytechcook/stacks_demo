<?php

namespace BWD\Stacks\Builder\Solr;


use BWConfig\SolrManager;
use BWD\Stacks\Alter\Solr\SolrAlterInterface;

/**
 * @deprecated
 */
class Solr implements SolrBuilderInterface {
  /**
   * @var SolrAlterInterface[]
   */
  private $alters = [];

  /**
   * Get an array of job ids in order
   *
   * @param array $filters
   *   Array of filters.
   * @see PlaylistManager::getFilterDefaults()
   *
   * @return array
   * - [$id => $source]
   */
  public function list(array $filters = []): array {
    $solr = $this->buildQuery($filters);

    $ids = $solr->executeQuery(NULL);

    $values = array_fill(0, count($ids), $this->source());

    return array_combine($ids, $values);
  }

  /**
   * Build out the Solr query necessary
   *
   * @param array $filters
   * @return \BWConfig\SolrManager
   */
  public function buildQuery(array $filters = []): SolrManager {
    $solr = new SolrManager(\apachesolr_get_solr());

    $fq = ['bundle' => 'job'];
    $solr->setFQ($fq);

    if (!empty($filters['title'])) {
      $solr->setQ($filters['title']);
    }

    $this->applyFilters($solr);

    return $solr;
  }

  private function applyFilters(SolrManager $query) {
    /** @var SolrAlterInterface $alter */
    foreach ($this->alters as $alter) {
      $alter->alterQuery($query);
    }
  }

  /**
   * Get source, type or machine name of the stack
   *
   * This will be used when tracking how a item as added
   * to a playlist
   *
   * @return string
   */
  public function source(): string {
    return 'solr';
  }

  /**
   * @param \BWD\Stacks\Alter\Solr\SolrAlterInterface $alter
   *
   * @return \BWD\Stacks\Builder\Solr\Solr
   */
  public function addAlter(SolrAlterInterface $alter): Solr {
    $this->alters[] = $alter;
    return $this;
  }

}