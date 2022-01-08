<?php

namespace BWStacks\Alter\Solr;


use BWConfig\ConfigNodeFactory;
use BWStacks\Alter\Solr\Boost\GoalBoost;
use BWStacks\Alter\Solr\Filter\Bundle;
use BWStacks\Alter\Solr\Filter\Internship;
use BWStacks\Alter\Solr\Filter\JobArea;
use BWStacks\Alter\Solr\Filter\JobBoard;
use BWStacks\Alter\Solr\Filter\Location;
use BWStacks\Alter\Solr\Filter\Org;
use BWStacks\ContentList\Solr\SolrList;

trait SolrFilterAlterTrait  {
  public function addSolrAlters(SolrList $playlist_solr, array $filters = []) {
    if (isset($filters['limit'])) {
      $playlist_solr->addAlter(new Limit($filters['limit']));
    }

    if (!empty($filters['location']) && $filters['location'] !== 'all') {
      if (is_array($filters['location'])) {
        $playlist_solr->addAlter(new Location($filters['location']));
      }
      else {
        $playlist_solr->addAlter(new Location(explode(',', $filters['location'])));
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

      $playlist_solr->addAlter(new Org($org));
    }

    if (!empty($filters['bundle'])) {
      $playlist_solr->addAlter(new Bundle($filters['bundle']));
    }
  }
}
