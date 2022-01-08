<?php

namespace BWStacks\ContentList\DrupalSelect;


use BWGoal\Manager\GoalManager;
use BWJob\LocationTaxonomy;
use BWStacks\Alter\DrupalSelect\FavoriteJob;
use BWStacks\Alter\DrupalSelect\Goal;
use BWStacks\Alter\DrupalSelect\Internship;
use BWStacks\Alter\DrupalSelect\Location;
use BWStacks\Alter\DrupalSelect\PassJob;
use BWStacks\Alter\DrupalSelect\Range;
use BWStacks\Alter\DrupalSelect\StatAlters;
use BWStacks\ListOf\StatsRemovableTrait;
use BWStacks\StacksConfig;
use BWUser\User;

trait DrupalSelectFilterAlterTrait {
  use StatsRemovableTrait;

  /**
   * Drupal select alters.
   *
   * @param \BWStacks\ContentList\DrupalSelect\NodeList $list
   *   Node list.
   * @param array $filters
   *   Filters.
   */
  public function addDrupalSelectAlters(NodeList $list, array $filters = []) {
    // Filter the results.
    if (!empty($filters['location']) && $filters['location'] !== 'all') {
      if (!is_array($filters['location'])) {
        $locations = explode(',', $filters['location']);
      }
      else {
        $locations = $filters['location'];
      }

      $locations = LocationTaxonomy::expandLocations($locations);
      $list->addAlter(new Location($locations));
    }

    if (!empty($filters['goal'])) {
      if ($goal_definition = GoalManager::getGoalDefinition($filters['goal'])) {
        if (isset($goal_definition['queryAlter']) &&
          isset($goal_definition['years']) &&
          isset($goal_definition['operator'])) {
          $queryAlter = new $goal_definition['queryAlter']((int) $goal_definition['years'], $goal_definition['operator']);
          $list->addAlter(new Goal($queryAlter));
        }
      }
    }

    if (isset($filters['limit'])) {
      $list->addAlter(new Range(0, $filters['limit']));
    }
    else {
      // For safety
      $list->addAlter(new Range(0, StacksConfig::QUERY_LIMIT));
    }

    if (isset($filters['internship'])) {
      $list->addAlter(new Internship((bool) $filters['internship']));
    }
  }

  /**
   * Specific alters for user.
   *
   * @param \BWStacks\ContentList\DrupalSelect\NodeList $list
   *   Node list.
   * @param \BWUser\User|NULL $user
   *   User object.
   * @param array $filters
   *   Filters.
   */
  public function addDrupalSelectAltersForUser(NodeList $list, User $user = NULL, array $filters = []) {
    // Add query alters
    if ($user && $this->isIncludeStatAlters()) {
      $stat_alter = new StatAlters($user);
      $stat_alter->setFilterDownvotes(FALSE)->setFilterUpvote(TRUE);
      $list->addAlter($stat_alter);
      $list->addAlter(new FavoriteJob($user, "IN"));
      $list->addAlter(new PassJob($user, "NOT IN"));
    }
  }

}
