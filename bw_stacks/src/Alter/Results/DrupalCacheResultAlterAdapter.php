<?php

namespace BWStacks\Alter\Results;


use BWD\Stacks\Alter\Results\ResultAlterInterface;

class DrupalCacheResultAlterAdapter implements ResultAlterInterface {

  /**
   * @var ResultAlterInterface
   */
  private $result_alter;
  /**
   * @var string
   */
  private $cache_id;
  /**
   * @var null
   */
  private $expire;

  /**
   * DrupalCacheResultAlterAdapter constructor.
   *
   * @param ResultAlterInterface $result_alter
   * @param string $cache_id
   * @param null $expire
   */
  public function __construct(ResultAlterInterface $result_alter, string $cache_id, $expire = NULL) {
    $this->result_alter = $result_alter;
    $this->cache_id = $cache_id;
    $this->expire = $expire ?? strtotime('+ 15 minutes');
  }


  /**
   * Alter the array of job ids
   *
   * @param array $results
   * - [$id => $source]
   *
   * @return array
   * - [[$id => $source], affected]
   */
  public function alter(array $results): array {
    $cached_results = &drupal_static(spl_object_hash($this), []);

    if (!$cached_results) {

      $cache = cache_get($this->cache_id);

      if ($cache && is_object($cache)) {
        $cached_results = $cache->data;
      }

      if (!$cached_results) {
        $cached_results = $this->result_alter->alter($results);
      }

      cache_set($this->cache_id, $cached_results, 'cache', $this->expire);
    }

    return $cached_results;
  }
}