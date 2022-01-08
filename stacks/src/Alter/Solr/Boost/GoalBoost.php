<?php

namespace BWD\Stacks\Alter\Solr\Boost;


use BWConfig\SolrManager;
use BWD\Stacks\Alter\Solr\SolrAlterInterface;

/**
 * @deprecated
 */
class GoalBoost implements SolrAlterInterface {

  /**
   * @var string
   */
  private $goal;

  /**
   * GoalBoast constructor.
   *
   * @param string $goal
   */
  public function __construct(string $goal) {
    $this->goal = $goal;
  }

  public function alterQuery(SolrManager $solr) {
    $goals_to_boost = [
      'summerinternship',
      'fallinternship',
    ];

    // If 'summer internship' or 'fall internship' goal set, boost
    // jobs that have the internship flag set and minimum experience
    // less than 2 years.
    if (in_array($this->goal, $goals_to_boost)) {
      $solr->addToBQ($solr->buildBoostQuery('bs_field_job_internship', 'true', 1.01, FALSE));
      $solr->addToBQ($solr->buildBoostQuery('its_field_job_minexperience', '* TO 2', 1.01, TRUE));
    }

    // If 'first career job' selected, boost jobs that have minimum
    // experience less than 5 years.
    if ($this->goal == 'firstjob') {
      $solr->addToBQ($solr->buildBoostQuery('its_field_job_minexperience', '2 TO 5', 1.01, TRUE));
      $solr->addToBQ($solr->buildBoostQuery('its_field_job_minexperience', '0', 1.01, FALSE));
    }
    
  }

}
