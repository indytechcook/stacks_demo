<?php

namespace BWD\Stacks\Factory;

use BWD\Stacks\Builder\Solr\CustomList;
use BWD\Stacks\Factory\GoalFilter;
use BWD\Stacks\ListCollection;
use BWD\Stacks\ListCollectionAdapter;
use BWD\Stacks\StackInterface;
use BWPlaylist\Playlist;
use BWUser\User;

/**
 * @deprecated
 */
class CustomPlaylistStackFactory implements StackFactoryInterface  {
  use ResultAlters;
  use SolrAlters;
  use GoalFilter;

  /**
   * @var \BWPlaylist\Playlist
   */
  private $playlist;
  /**
   * @var \BWUser\User
   */
  private $user;

  /**
   * CustomPlaylistFactory constructor.
   *
   * @param \BWPlaylist\Playlist $playlist
   * @param \BWUser\User $user
   */
  public function __construct(Playlist $playlist, User $user = NULL) {
    $this->playlist = $playlist;
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
  public function build(array $filters = [], bool $cache = FALSE): StackInterface {
    $list = new CustomList($this->playlist);

    // Goal from playlist object.
    //$filters['goal'] = $this->playlist->getGoal();
    //$filters = $this->alterFiltersForGoal($filters);
    $this->addSolrAlters($list, $filters);

    $lists = new ListCollection();
    $lists->addList($list);
    $lists = new ListCollectionAdapter($lists);

    if ($this->user) {
      $this->addAlters($lists, $this->user);
    }

    return $lists;
  }
}
