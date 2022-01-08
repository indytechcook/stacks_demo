<?php

namespace BWD\Stacks\Alter\Results;

trait Moveable {
  /**
   * Moves viewed jobs to the end of the list.
   *
   * @param $results array
   * - array [$id -> $source]
   * @param $ids_to_move array
   *    job ids
   *
   * @return array
   *   reordered unique job ids
   *   - array [$id => $source]
   */
  protected function moveToBack(array $results, array $ids_to_move): array {
    if (!empty($ids_to_move)) {
      $moved_array = array_combine($ids_to_move, $ids_to_move);

      $back_array = array_intersect_key($results, $moved_array);
      $front_array = array_diff_key($results, $back_array);
      $results = $front_array + $back_array;
    }

    return $results;
  }

  /**
   * Moves 'front' elements found in 'all' array to the beginning, returns new arary.
   *
   * @param $results array
   * - array [$id -> $source]
   * @param $ids_to_move array
   * - Array of job ids
   *
   * @return array
   * - Array [$id => $source]
   */
  protected function moveToFront(array $results, array $ids_to_move): array {
    if (!empty($ids_to_move)) {
      $moved_array = array_combine($ids_to_move, $ids_to_move);

      $new_front = array_intersect_key($results, $moved_array);
      $new_back = array_diff_key($results, $new_front);

      $results = $new_front + $new_back;
    }

    return $results;
  }
}