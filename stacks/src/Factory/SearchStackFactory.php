<?php

namespace BWD\Stacks\Factory;


use BWD\Stacks\Builder\Solr\Solr;
use BWD\Stacks\ListCollection;
use BWD\Stacks\ListCollectionAdapter;
use BWD\Stacks\StackInterface;
use BWUser\User;

/**
 * @deprecated
 */
class SearchStackFactory implements StackFactoryInterface  {
  use SolrAlters;
  use GoalFilter;

  /**
   * @var \BWUser\User
   */
  private $user;

  public function __construct(User $user = NULL) {
    $this->user = $user;
  }

  /**
   * Build a Playlist for use in Feed class
   *
   * @param array $filters
   *
   * @param bool $cache
   * @return \BWD\Stacks\StackInterface
   */
  public function build(array $filters = [], bool $cache = TRUE): StackInterface {
    // Search playlist uses solr
    $list_solr = new Solr();

    $this->addSolrAlters($list_solr, $filters);

    $lists = new ListCollection();
    $lists->addList($list_solr);

    return new ListCollectionAdapter($lists);
  }
}