<?php

namespace BWD\Stacks\Factory;


use BWConfig\ConfigNodeFactory;
use BWD\Stacks\Alter\Solr\Boost\GoalBoost;
use BWD\Stacks\Alter\Solr\Filter\Bundle;
use BWD\Stacks\Alter\Solr\Filter\Internship;
use BWD\Stacks\Alter\Solr\Filter\JobArea;
use BWD\Stacks\Alter\Solr\Filter\JobBoard;
use BWD\Stacks\Alter\Solr\Filter\Location;
use BWD\Stacks\Alter\Solr\Filter\Org as SolrOrg;
use BWD\Stacks\Builder\Solr\Solr;
use BWOrg\Org;

/**
 * @deprecated
 */
trait SolrAlters {
  public function addSolrAlters(Solr $playlist_solr, array $filters = []) {
    if (!empty($filters['location']) && $filters['location'] !== 'all') {
      if (is_array($filters['location'])) {
        $playlist_solr->addAlter(new Location($filters['location']));
      }
      else {
        $playlist_solr->addAlter(new Location(explode(',', $filters['location'])));
      }
    }

    if (!empty($filters['jobarea']) && $filters['jobarea'] !== 'all') {
      if (!is_array($filters['jobarea'])) {
        $playlist_solr->addAlter(new JobArea(explode(',', $filters['jobarea'])));
      }
      else {
        $playlist_solr->addAlter(new JobArea($filters['jobarea']));
      }
    }

    if (isset($filters['internship'])) {
      $playlist_solr->addAlter(new Internship((bool) $filters['internship']));
    }

    if (isset($filters['exclude_jobboard'])) {
      $playlist_solr->addAlter(new JobBoard((bool) $filters['exclude_jobboard']));
    }

    if (!empty($filters['goal'])) {
      $playlist_solr->addAlter(new GoalBoost($filters['goal']));
    }

    if (!empty($filters['org'])) {
      if (is_numeric($filters['org'])) {
        /** @var Org $org */
        $org = ConfigNodeFactory::createByNid($filters['org']);
      } else {
        $org = $filters['org'];
      }

      $playlist_solr->addAlter(new SolrOrg($org));
    }

    if (!empty($filters['bundle'])) {
      $playlist_solr->addAlter(new Bundle($filters['bundle']));
    }
  }
}