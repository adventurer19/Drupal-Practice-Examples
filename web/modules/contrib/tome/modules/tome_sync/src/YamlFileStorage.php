<?php

namespace Drupal\tome_sync;

use Drupal\Core\Config\FileStorage;
use Symfony\Component\Yaml\Yaml;

/**
 * Defines the yaml file storage.
 *
 * @internal
 */
class YamlFileStorage extends FileStorage {

  /**
   * {@inheritdoc}
   */
  public static function getFileExtension() {
    return 'yml';
  }

  /**
   * {@inheritdoc}
   */
  public function encode($data) {
    // CRLF breaks multi line literals.
    if (is_array($data)) {
      array_walk_recursive($data, function (&$item, $key) {
        if (is_string($item)) {
          $item = str_replace("\r\n", "\n", $item);
        }
      });
    }
    $yaml = \Drupal::service('serializer')->encode($data, 'tome_sync_yaml', [
      'yaml_flags' => Yaml::DUMP_MULTI_LINE_LITERAL_BLOCK,
      'yaml_inline' => PHP_INT_MAX,
    ]);
    // Naively make all arrays inline to reduce number of newlines.
    return preg_replace('/(?<!\|)\-\n\s+/', '- ', $yaml);
  }

  /**
   * {@inheritdoc}
   */
  public function decode($data) {
    return \Drupal::service('serializer')->decode($data, 'tome_sync_yaml');
  }

}
