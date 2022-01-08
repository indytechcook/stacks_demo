<?php

namespace BWStacks\Alter\Solr;


use BWConfig\SolrManager;

class RequestHandler implements SolrAlterInterface {

  /**
   * @var string
   */
  private $request_type;

  /**
   * RequestHandler constructor.
   * @param $request_type
   */
  public function __construct(string $request_type) {
    $this->request_type = $request_type;
  }


  /**
   * @param \BWConfig\SolrManager $solr
   */
  public function alterQuery(SolrManager $solr) {
    $solr->setRequestType($this->request_type);
  }
}