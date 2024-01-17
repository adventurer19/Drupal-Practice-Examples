<?php

namespace Drupal\tome_sync;

use Drupal\Core\StreamWrapper\StreamWrapperManager;

/**
 * Contains shared methods for cleaning files.
 */
trait CleanFilesTrait {

  /**
   * Assembles a list of files that are unused.
   *
   * @return array
   *   An associative array mapping file UUIDs to their URIs.
   */
  protected function getUnusedFiles() {
    $files = [];
    $names = $this->contentStorage->listAll('file.');
    foreach ($names as $name) {
      $data = $this->contentStorage->read($name);
      list(, $uuid) = TomeSyncHelper::getPartsFromContentName($name);
      $files[$uuid] = StreamWrapperManager::getTarget($data['uri'][0]['value']);
    }
    $callback = function ($value) use (&$files) {
      if (is_string($value)) {
        foreach ($files as $uuid => $filename) {
          if (strpos($value, $uuid) !== FALSE || strpos($value, $filename) !== FALSE) {
            unset($files[$uuid]);
          }
        }
      }
    };
    $names = array_diff($this->contentStorage->listAll(), $names);
    foreach ($names as $name) {
      if (!$files) {
        break;
      }
      $data = $this->contentStorage->read($name);
      array_walk_recursive($data, $callback);
    }
    $names = $this->configStorage->listAll();
    foreach ($names as $name) {
      if (!$files) {
        break;
      }
      $data = $this->configStorage->read($name);
      array_walk_recursive($data, $callback);
    }
    return $files;
  }

}
