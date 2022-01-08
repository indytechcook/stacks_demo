<?php

namespace BWD\Stacks\Factory;


use BWCip\CipTaxonomy;
use BWConfig\ConfigNodeFactory;
use BWConfig\ConfigNodeFactoryException;
use BWD\Stacks\Alter\DrupalSelect\Goal;
use BWD\Stacks\Alter\DrupalSelect\Internship;
use BWD\Stacks\Alter\DrupalSelect\Location;
use BWD\Stacks\Alter\DrupalSelect\StatAlters;
use BWD\Stacks\Alter\Solr\Boost\DownVote;
use BWD\Stacks\Alter\Solr\Boost\SourceBoost;
use BWD\Stacks\Alter\Solr\Boost\UpVote;
use BWD\Stacks\Alter\Solr\Filter\DownVote as DownVoteFilter;
use BWD\Stacks\Builder\Jobs\CIPList;
use BWD\Stacks\Builder\Jobs\MatchesList;
use BWD\Stacks\Builder\Solr\CustomList;
use BWD\Stacks\Builder\Solr\DegreeProgramSolr;
use BWD\Stacks\DrupalCacheAdapter;
use BWD\Stacks\EmptyList;
use BWD\Stacks\ListCollection;
use BWD\Stacks\ListCollectionAdapter;
use BWD\Stacks\ListInterface;
use BWD\Stacks\StackInterface;
use BWGoal\Manager\GoalManager;
use BWJob\LocationTaxonomy;
use BWJob\Manager\JobManager;
use BWUser\User;

/**
 * @deprecated
 */
class SuggestStackFactory implements StackFactoryInterface  {
  use ResultAlters;
  use GoalFilter;
  use SolrAlters;

  /**
   * @var \BWUser\User
   */
  private $user;

  public function __construct(User $user) {
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
    // Remove job board filter
    unset($filters['exclude_jobboard']);

    $matches_list = $this->buildMatchesList($filters);
    $cip_list = $this->buildCIPList($filters);
    $dp_list = $this->buildDegreeProgramList($filters);
    $pl_list = $this->buildPlaylistStacks($filters);

    $list_collection = new ListCollection();
    $list_collection->addList($pl_list);
    $list_collection->addList($cip_list, $cache);
    $list_collection->addList($matches_list, $cache);
    $list_collection->addList($dp_list, $cache);

    $list = new ListCollectionAdapter($list_collection);

    $this->addAlters($list, $this->user);

    return $list;
  }

  /**
   * Build a User Match Playlist
   *
   * @param array $filters
   * @param bool $cache
   *
   * @return \BWD\Stacks\ListInterface
   */
  public function buildMatchesList(array $filters = [], $cache = FALSE): ListInterface {
    $matches_list = new MatchesList($this->user);

    // Filter the results.
    if (!empty($filters['location']) && $filters['location'] != 'all') {
      if (!is_array($filters['location'])) {
        $locations = explode(',', $filters['location']);
      }
      else {
        $locations = $filters['location'];
      }

      $locations = LocationTaxonomy::expandLocations($locations);
      $matches_list->addAlter(new Location($locations));
    }

    // Add query alters
    $stat_alter = new StatAlters($this->user);
    $stat_alter->setFilterDownvotes(TRUE)->setFilterUpvote(TRUE);
    $matches_list->addAlter($stat_alter);

    if (isset($filters['internship'])) {
      $matches_list->addAlter(new Internship((bool) $filters['internship']));
    }

    if ($cache) {
      $cache_array = [
        'list-matches',
        $this->user->getId()
      ];

      $cache_id = implode('-', $cache_array);
      // Cache until midnight UTC
      return new DrupalCacheAdapter($matches_list, $cache_id, strtotime('today midnight'));
    }

    return $matches_list;
  }

  /**
   * Build the CIP/Blue Orca stack
   *
   * @param array $filters
   * @param bool $cache
   *
   * @return \BWD\Stacks\ListInterface
   */
  public function buildCIPList(array $filters = [], $cache = FALSE): ListInterface {
    $cip_ids = $this->user->getCIPIds();
    if ($this->user->useCip() &&
      $cip_ids &&
      CipTaxonomy::getONETIdsByCIPIds($cip_ids)
    ) {

      $cip_playlist = new CIPList($cip_ids);

      // Filter the results.
      if (!empty($filters['location']) && $filters['location'] != 'all') {
        if (!is_array($filters['location'])) {
          $locations = explode(',', $filters['location']);
        }
        else {
          $locations = $filters['location'];
        }

        $locations = LocationTaxonomy::expandLocations($locations);
        $cip_playlist->addAlter(new Location($locations));
      }

      if (!empty($filters['goal'])) {
        if ($goal_definition = GoalManager::getGoalDefinition($filters['goal'])) {
          if (isset($goal_definition['queryAlter']) &&
            isset($goal_definition['years']) &&
            isset($goal_definition['operator'])) {
            $queryAlter = new $goal_definition['queryAlter']((int) $goal_definition['years'], $goal_definition['operator']);
            $cip_playlist->addAlter(new Goal($queryAlter));
          }
        }
      }

      // Add query alters
      $stat_alter = new StatAlters($this->user);
      $stat_alter->setFilterDownvotes(TRUE)->setFilterUpvote(TRUE);
      $cip_playlist->addAlter($stat_alter);

      if (isset($filters['internship'])) {
        $cip_playlist->addAlter(new Internship((bool) $filters['internship']));
      }

      if ($cache) {
        $cache_array = [
          'list-cips',
          $cip_ids
        ];

        $cip_playlist = new DrupalCacheAdapter($cip_playlist, implode('-', $cache_array));
      }
    }
    else {
      $cip_playlist = new EmptyList();
    }

    return $cip_playlist;
  }

  /**
   * Build the solr based degree program stack
   *
   * @param array $filters
   * @param bool $cache
   *
   * @return \BWD\Stacks\ListInterface
   */
  public function buildDegreeProgramList(array $filters = [], $cache = FALSE): ListInterface {
    $degree_programs = $this->user->getDegreePrograms();

    if ($degree_programs) {
      $list = new DegreeProgramSolr($degree_programs);

      $this->addSolrAlters($list, $filters);

      foreach ($degree_programs as $program) {
        if ($program->field_taxonomy_boost_source->value()) {
          $list->addAlter(new SourceBoost());
        }
      }

      $job_manager = new JobManager($this->user);
      $down_votes = array_unique($job_manager->getDownvotedJobIds());
      $up_votes = array_unique($job_manager->getUpVotedJobIds());

      if ($down_votes) {
        $list->addAlter(new DownVoteFilter($down_votes));
        $list->addAlter(new DownVote($this->user, $down_votes));
      }

      if ($up_votes) {
        $list->addAlter(new UpVote($this->user, $up_votes));
      }

      if ($cache) {
        $cache_array = [
          'list-dp',
          implode('_', $degree_programs->value(['identifier' => TRUE]))
        ];

        $list = new DrupalCacheAdapter($list, implode('-', $cache_array));
      }
    }
    else {
      $list = new EmptyList();
    }

    return $list;
  }

  /**
   * Build a custom playlist
   *
   * @param array $filters
   * @return \BWD\Stacks\ListInterface
   */
  public function buildPlaylistStacks(array $filters = []) : ListInterface {
    // Get playlists for users
    if ($this->user->getOrg()->getDefaultPlaylistType()) {
      $nid = $this->user->getOrg()->getDefaultPlaylistType()->nid;

      try {
        $playlist = ConfigNodeFactory::createByNid($nid, 'playlist');

        if (!$playlist) {
          return new EmptyList();
        }

        $list = new CustomList($playlist);
        //$filters['goal'] = $playlist->getGoal();
        //$filters = $this->alterFiltersForGoal($filters);

        $this->addSolrAlters($list, $filters);
      } catch (ConfigNodeFactoryException $e) {
        watchdog_exception('STACK', $e);
        $list = new EmptyList();
      }
    } else {
      $list = new EmptyList();
    }

    return $list;
  }
}
