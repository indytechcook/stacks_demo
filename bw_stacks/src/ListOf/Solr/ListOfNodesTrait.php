<?php

namespace BWStacks\ListOf\Solr;


use BWStacks\Alter\Solr\Filter\Bundle;
use BWStacks\ContentList\Solr\SolrList;

trait ListOfNodesTrait {
  protected function baseList(string $type, string $source) : SolrList {
    $solr_list = new SolrList();
    $solr_list->setType($type);
    $solr_list->setSource($source);
    $solr_list->addAlter(new Bundle('job'));

    return $solr_list;
  }
}