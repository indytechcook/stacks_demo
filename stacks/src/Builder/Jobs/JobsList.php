<?php


namespace BWD\Stacks\Builder\Jobs;

use BWD\Stacks\Alter\DrupalSelect\DrupalSelectAlterInterface;
use BWD\Stacks\Builder\DrupalSelectListInterface;
use BWJob\Job;
use SelectQuery;

/**
 * @deprecated
 */
class JobsList implements DrupalSelectListInterface  {
  /**
   * @var DrupalSelectAlterInterface[]
   */
  private $alters = [];

  /**
   * Get an array of job ids in order
   *
   * @param array $filters
   * @return array
   * - [$id => $source]
   */
  public function list(array $filters = []): array {
    $query = $this->buildQuery($filters);
    $ids = $query->execute()->fetchCol();

    $values = array_fill(0, count($ids), $this->source());

    return array_combine($ids, $values);
  }

  /**
   * Build the query
   *
   * @param array $filters
   * @return \SelectQuery
   */
  public function buildQuery(array $filters): SelectQuery {
    // @TODO ideally you would pass in a querybuilder class...
    $query = db_select('node', 'n', ['target' => 'slave']);
    $query->fields('n', ['nid']);
    $query->condition('n.status', 1);
    $query->condition('n.type', 'job');
    $query->condition('n.created', REQUEST_TIME - Job::LIFETIME, '>');
    $query->orderBy('n.nid', 'DESC');

    $start = $filters['start'] ?? 0;
    $limit = $filters['limit'] ?? 100;

    $query->range($start, $limit);

    $this->applyAlters($query);

    return $query;
  }

  private function applyAlters(SelectQuery $query) {
    /** @var DrupalSelectAlterInterface $alter */
    foreach ($this->alters as $alter) {
      $alter->alter($query);
    }
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
    return 'job';
  }

  public function addAlter(DrupalSelectAlterInterface $alter) {
    $this->alters[] = $alter;
  }
}