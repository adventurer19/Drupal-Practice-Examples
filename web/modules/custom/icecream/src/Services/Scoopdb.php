<?php

namespace Drupal\icecream\Services;

use Drupal\Core\Database\Connection;

/**
 * Class Scoopdb.
 */
class Scoopdb {

  /**
   * The database connection.
   *
   * @var \Drupal\Core\Database\Connection
   */
  protected Connection $database;

  /**
   * Constructs a new Scoopdb object.
   *
   * @param \Drupal\Core\Database\Connection $connection
   *   The database connection.
   */
  public function __construct(Connection $connection) {
    $this->database = $connection;
  }

  /**
   * Returns list of nids from icecream table.
   */
  public function icecream() {
    $query = $this->database->query('SELECT nid FROM {icecream}');
    $result = $query->fetchAssoc();
    return $result;
  }

}
