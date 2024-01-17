<?php

namespace Drupal\example\Services;

use Drupal\Core\Database\Connection;
/**
 * Class Scoopdb.
 */
class Scoopdb  {
  /**
   * @var \Drupal\Core\Database\Connection $database
   */
  protected $database;

  /**
   * Constructs a new Scoopdb object.
   * @param \Drupal\Core\Database\Connection $connection
   */
  public function __construct(Connection $connection) {
    $this->database = $connection;
  }

  /**
   * Returns list of nids from icecream table.
   */
  public function icecream () {
    $query = $this->database->query('SELECT nid FROM {icecream}');
    $result = $query->fetchAssoc();
    return $result;
  }

}
