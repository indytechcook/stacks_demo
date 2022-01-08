<?php

namespace BWStacks\Alter\DrupalSelect\Taxonomy;


use BWStacks\Alter\DrupalSelect\DrupalSelectAlterInterface;

class Vocab implements DrupalSelectAlterInterface {
  /**
   * @var string
   */
  private $vocab;

  /**
   * Vocab constructor.
   * @param string $vocab
   */
  public function __construct($vocab) {
    $this->vocab = $vocab;
  }


  /**
   * @param \SelectQuery $query
   */
  public function alter(\SelectQuery $query) {
    // Get vid
    $alias = $query->innerJoin('taxonomy_vocabulary', 'v', 't.vid = v.vid');
    $query->condition("$alias.machine_name", $this->vocab);
  }
}