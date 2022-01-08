<?php

namespace BWD\Stacks;

/**
 * @deprecated
 */
class EmptyList implements ListInterface   {

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
    return [];
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
    return 'empty';
  }
}