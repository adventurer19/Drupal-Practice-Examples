<?php

namespace Drupal\tome_sync;

use Drupal\Core\Site\Settings;

/**
 * Provides a factory for creating yaml file storage objects.
 *
 * @internal
 */
class YamlFileStorageFactory {

  /**
   * Returns a YamlFileStorage object.
   *
   * @return \Drupal\tome_sync\YamlFileStorage
   *   The Yaml file storage.
   */
  public static function getContent() {
    return new YamlFileStorage(Settings::get('tome_content_directory', '../content'));
  }

}
