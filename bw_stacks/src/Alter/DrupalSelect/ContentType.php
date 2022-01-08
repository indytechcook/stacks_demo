<?php

namespace BWStacks\Alter\DrupalSelect;


class ContentType implements DrupalSelectAlterInterface {

  /**
   * @var string
   */
  private $types;

  /**
   * ContentType constructor.
   *
   * @param string or array $types
   *   The content type to filter on
   */
  public function __construct($types) {
    if (!is_array($types)) {
      $types = [$types];
    }
    $this->types = $types;
  }

  public function alter(\SelectQuery $query) {
    $query->condition('n.type', $this->types, 'IN');
  }
}