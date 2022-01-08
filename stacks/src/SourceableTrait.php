<?php

namespace BWD\Stacks;


trait SourceableTrait {
  /**
   * @var string
   */
  protected $source;

  /**
   * Get source, type or machine name of the playlist
   *
   * This will be used when tracking how a item as added
   * to a playlist
   *
   * @return string
   */
  public function source(): string {
    return $this->source;
  }

  /**
   * @param string $source
   */
  public function setSource(string $source) {
    $this->source = $source;
  }
}