<?php

namespace BWStacks\Filter;


use BWD\Stacks\Factory\GoalFilter;
use BWDisplay\Input\BooleanInput;
use BWDisplay\Input\IntegerInput;
use BWDisplay\Input\StringInput;
use BWDisplay\ProcessInputs;
use BWOrg\Org;
use BWUser\User;

class FilterBuilder {
  use GoalFilter;

  /**
   * @var string
   */
  const SESSION_STR = 'job_filters';
  /**
   * @var User
   */
  private $user;
  /**
   * @var Org
   */
  private $org;

  /**
   * FilterBuilder constructor.
   * @param \BWUser\User $user
   * @param $org
   */
  public function __construct(\BWUser\User $user = NULL, Org $org = NULL) {
    if ($user) {
      $this->user = $user;
      $this->org = $user->getOrg();
    }
    elseif ($org) {
      $this->org = $org;
    }
  }

  /**
   * Save the filters
   *
   * @param array $filters
   */
  public static function saveFilters(array $filters) {
    $_SESSION[self::SESSION_STR] = $filters;
    drupal_session_commit();
  }

  public function buildFilters(array $filters = []) : array {
    if (empty($filters)) {
      $filters = self::filtersFromURL() ?? [];
    }

    if (empty($filters) && !$this->user) {
      $filters = self::getFilterDefaults();

    }

    // Check the session filters
    if (empty($filters)) {
      $filters = $_SESSION[self::SESSION_STR] ?? [];
    }

    if (empty($filters)) {
      // Override if user set their own preferences.
      if ($this->user->getLocationIds()) {
        $filters['location'] = $this->user->getLocationIds();
      }
      if ($this->user->getJobAreaIds()) {
        $filters['jobarea'] = $this->user->getJobAreaIds();
      }

      // Otherwise check the job prefs for this user
      if (empty($filters['location']) && $this->org->getDefaultLocationIds()) {
        $filters['location'] = $this->org->getDefaultLocationIds();
      }

      if (empty($filters['jobarea']) && $this->org->getDefaultJobAreaIds()) {
        $filters['jobarea'] = $this->org->getDefaultJobAreaIds();
      }

      if (empty($filters['goal']) && $this->user->getGoal()) {
        $filters['goal'] = $this->user->getGoal();
      }
    }

    if (empty($filters)) {
      // Defaults
      $filters = self::getFilterDefaults();
    }

    if (isset($filters['location']) && is_array($filters['location'])) {
      $filters['location'] = implode(',', $filters['location']);
    }

    $filters = $this->alterFiltersForGoal($filters);

    return $filters;
  }

  public static function filtersFromURL(): array {
    $process_inputs = new ProcessInputs();
    $process_inputs->addInput(new StringInput('type', NULL));
    $process_inputs->addInput(new StringInput('location', NULL));
    $process_inputs->addInput(new StringInput('jobarea', NULL));
    $process_inputs->addInput(new BooleanInput('internship', NULL));
    $process_inputs->addInput(new BooleanInput('exclude_jobboard', NULL));
    $process_inputs->addInput(new StringInput('title', NULL));
    $process_inputs->addInput(new StringInput('type', NULL));
    $process_inputs->addInput(new BooleanInput('skipcache', NULL));
    $process_inputs->addInput(new StringInput('goal', NULL));
    $process_inputs->addInput(new BooleanInput('conversation', NULL));

    $filters = $process_inputs->processInput(INPUT_GET);

    // If any of the url params are null then do not return them
    $return = [];
    foreach ($filters as $key => $filter) {
      if (!is_null($filter)) {
        $return[$key] = $filter;
      }
    }

    return $return;
  }

  /**
   * Get default filters.
   */
  public static function getFilterDefaults() {
    return [
      'location' => 'all',
      'jobarea' => 'all',
      'internship' => 0,
      'exclude_jobboard' => 1,
      'title' => ''
    ];
  }
}
