<?php

namespace BWD\Stacks\Alter\Results;

trait ResultMergeTrait {
  /**
   * Merge the new ids with the current result set
   *
   * @param array $org_results
   * - array [$id => $source]
   *
   * @param array $new_ids
   * - array $ids
   * @param string $type
   * - The source ot use for the new ides
   * @param bool $move_to_front
   * - Move the new ids to the front
   *
   * @return array
   * - array [$id => $srouce]
   */
  protected function mergeResults(array $org_results, array $new_ids, string $type, $move_to_front = FALSE) {
    $new_values = array_fill(0, count($new_ids), $type);
    $new_results = array_combine($new_ids, $new_values);

    if ($move_to_front) {
      return $new_results + $org_results;
    }

    return $org_results + $new_results;
  }

  protected function diffResults(array $org_results, array $ids_to_remove) {
    $array_to_remove = array_combine($ids_to_remove, $ids_to_remove);
    return array_diff_key($org_results, $array_to_remove);
  }
}