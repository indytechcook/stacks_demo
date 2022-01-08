<?php

namespace BWStacks;

use BWD\Stacks\ContentList\ContentListInterface;
use BWD\Stacks\NewStackInterface;
use BWD\Stacks\Result\ResultAlterableTrait;
use BWD\Stacks\Result\ResultCollection;


/**
 * @TODO update for ContentListInterface
 */
class DrupalCacheAdapter implements NewStackInterface {

  use ResultAlterableTrait;

  /**
   * @var ContentListInterface
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
   * @param ContentListInterface $stack
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
  public function __construct(ContentListInterface $stack, string $cache_id, $expire = NULL) {
    $this->stack = $stack;
    $this->cache_id = $cache_id;

    $this->expire = $expire ?? strtotime('+ 15 minutes');
  }

  /**
   * Get an array of job ids in order
   *
   * @param \BWD\Stacks\Result\ResultCollection $results
   * @return array|\BWD\Stacks\Result\ResultCollection
   */
  public function list(ResultCollection $results): ResultCollection {
    $return = clone $results;
    // Check cache

    $cache = cache_get($this->cache_id);

    if ($cache && is_object($cache)) {
      $return = $cache->data;
    } else {
      $return = $this->stack->list($return);
      cache_set($this->cache_id, $return, 'cache', $this->expire);
    }

    $this->alter($return);

    return $return;
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