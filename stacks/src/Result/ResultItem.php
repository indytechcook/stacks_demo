<?php

namespace BWD\Stacks\Result;


class ResultItem {
  /**
   * @var int
   */
  private $id;
  /**
   * @var string
   */
  private $source;
  /**
   * @var string
   */
  private $type;
  /**
   * @var boolean
   */
  private $altered = FALSE;
  /**
   * @var string
   */
  private $alteredBy;

  const SEPERATOR = ':::';

  /**
   * ResultItem constructor.
   * @param int $id
   * @param string $source
   * @param $type
   * @param $altered
   * @param $alteredBy
   */
  public function __construct(int $id, string $source, string $type, bool $altered = FALSE, string $alteredBy = '') {
    $this->id = $id;
    $this->source = $source;
    $this->type = $type;
    $this->altered = $altered;
    $this->alteredBy = $alteredBy;
  }

  /**
   * @return int
   */
  public function getId(): int {
    return $this->id;
  }

  /**
   * @return string
   */
  public function getSource(): string {
    return $this->source;
  }

  /**
   * @return string
   */
  public function getType() : string {
    return $this->type;
  }

  /**
   * @return bool
   */
  public function isAltered(): bool {
    return $this->altered;
  }

  /**
   * @param bool $altered
   */
  public function setAltered(bool $altered) {
    $this->altered = $altered;
  }

  /**
   * @return string
   */
  public function getAlteredBy(): string {
    return $this->alteredBy;
  }

  /**
   * @param string $alteredBy
   */
  public function setAlteredBy(string $alteredBy) {
    $this->alteredBy = $alteredBy;
  }

  public function asArray(): array {
    return [
      'id' => $this->getId(),
      'source' => $this->getSource(),
      'type' => $this->getType(),
      'altered' => $this->isAltered(),
      'alteredBy' => $this->getAlteredBy(),
    ];
  }

  public static function buildFromArray(array $array) {
    return new self($array['id'], $array['source'], $array['type'], $array['altered'] ?? FALSE, $array['alteredBy'] ?? NULL);
  }

  public function uniqueId() {
    return $this->type . self::SEPERATOR . $this->id;
  }
}
