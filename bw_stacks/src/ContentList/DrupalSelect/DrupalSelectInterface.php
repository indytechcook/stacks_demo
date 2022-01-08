<?php

namespace BWStacks\ContentList\DrupalSelect;


use BWD\Stacks\ContentList\ContentListInterface;
use BWStacks\Alter\DrupalSelect\DrupalSelectAlterInterface;

interface DrupalSelectInterface extends ContentListInterface {
  /**
   * Build the query
   *
   * @return \SelectQuery
   */
  public function buildQuery() : \SelectQuery;

  /**
   * Add a Alter to the query
   *
   * @param \BWStacks\Alter\DrupalSelect\DrupalSelectAlterInterface $alter
   * @return mixed
   */
  public function addAlter(DrupalSelectAlterInterface $alter);

  /**
   * Apply alters to a query
   *
   * @param \SelectQuery $query
   * @return mixed
   */
  public function applyAlters(\SelectQuery $query);
}