<?php

namespace BWStacks\Alter\Solr;

use BWConfig\SolrManager;

interface SolrAlterInterface {
  public function alter(SolrManager $solr);
}