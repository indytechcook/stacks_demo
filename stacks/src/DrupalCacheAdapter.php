<?php

namespace BWD\Stacks;


use BWD\Stacks\Alter\ResultAlterable;
use BWD\Stacks\Builder\ResultAlterableInterface;

/**
 * @deprecated
 */
class DrupalCacheAdapter implements ListInterface, ResultAlterableInterface {

  use ResultAlterable;

  /**
   * @var ListInterface
   */
  private $stack;
  /**
   * @var string
   */
  private $cache_id;
  private $expire;

  /**
   * PlaylistCacheAdapter constructor.
   *
   * @param ListInterface $stack
   * @param string $cache_id
   * @param $expire
   *   (optional) One of the following values:
   *   - CACHE_PERMANENT: Indicates that the item should never be removed unless
   *     explicitly told to using cache_clear_all() with a cache ID.
   *   - CACHE_TEMPORARY: Indicates that the item should be removed at the next
   *     general cache wipe.
   *   - A Unix timestamp: Indicates that the item should be kept at least until
   *     the given time, after which it behaves like CACHE_TEMPORARY.
   */
  public function __construct(ListInterface $stack, string $cache_id, $expire = NULL) {
    $this->stack = $stack;
    $this->cache_id = $cache_id;

    $this->expire = $expire ?? strtotime('+ 15 minutes');
  }

  /**
   * Get an array of job ids in order
   *
   * @param array $filters
   * @return array
   */
  public function list(array $filters = []): array {
    $results = &drupal_static(spl_object_hash($this), []);

    if (!$results) {
      // Check cache
      sort($filters);

      $cache_id = $this->cache_id . '_' . str_replace(',', '_', implode('_', $filters));

      $cache = cache_get($cache_id);

      if ($cache && is_object($cache)) {
        $results = $cache->data;
      }

      if (!$results) {
        $results = $this->stack->list($filters);
      }

      foreach ($this->resultAlters as $alter) {
        $results = $alter->alter($results);
      }

      cache_set($cache_id, $results, 'cache', $this->expire);
    }

    return $results;
  }


  /**
   * Get source, type or machine name of the playlist
   *
   * This will be used when tracking how a item as added
   * to a playlist
   *
   * @return string
   */
  public function source(): string {
    return 'cache';
  }
}