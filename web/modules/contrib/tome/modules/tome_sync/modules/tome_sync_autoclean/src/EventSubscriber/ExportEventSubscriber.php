<?php

namespace Drupal\tome_sync_autoclean\EventSubscriber;

use Drupal\Core\Config\ConfigCrudEvent;
use Drupal\Core\Config\ConfigEvents;
use Drupal\Core\Config\StorageInterface;
use Drupal\Core\File\FileSystemInterface;
use Drupal\tome_base\PathTrait;
use Drupal\tome_sync\CleanFilesTrait;
use Drupal\tome_sync\ContentIndexerTrait;
use Drupal\tome_sync\Event\ContentCrudEvent;
use Drupal\tome_sync\Event\TomeSyncEvents;
use Drupal\tome_sync\FileSyncInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Automatically deletes unused files after content/config is exported.
 *
 * @internal
 */
class ExportEventSubscriber implements EventSubscriberInterface {

  use PathTrait;
  use ContentIndexerTrait;
  use CleanFilesTrait;

  /**
   * The target content storage.
   *
   * @var \Drupal\Core\Config\StorageInterface
   */
  protected $contentStorage;

  /**
   * The config storage.
   *
   * @var \Drupal\Core\Config\StorageInterface
   */
  protected $configStorage;

  /**
   * The file system.
   *
   * @var \Drupal\Core\File\FileSystemInterface
   */
  protected $fileSystem;

  /**
   * The file sync service.
   *
   * @var \Drupal\tome_sync\FileSyncInterface
   */
  protected $fileSync;

  /**
   * Creates a ExportEventSubscriber object.
   *
   * @param \Drupal\Core\Config\StorageInterface $content_storage
   *   The target content storage.
   * @param \Drupal\Core\Config\StorageInterface $config_storage
   *   The target config storage.
   * @param \Drupal\tome_sync\FileSyncInterface $file_sync
   *   The file sync service.
   * @param \Drupal\Core\File\FileSystemInterface $file_system
   *   The file system.
   */
  public function __construct(StorageInterface $content_storage, StorageInterface $config_storage, FileSyncInterface $file_sync, FileSystemInterface $file_system) {
    $this->contentStorage = $content_storage;
    $this->configStorage = $config_storage;
    $this->fileSync = $file_sync;
    $this->fileSystem = $file_system;
  }

  /**
   * Removes unused files.
   */
  protected function deleteUnusedFiles() {
    $files = $this->getUnusedFiles();
    foreach ($files as $uuid => $filename) {
      $this->contentStorage->delete("file.$uuid");
      $this->unIndexContentByName("file.$uuid");
      $this->fileSync->deleteFile($filename);
    }
  }

  /**
   * Reacts to content events.
   *
   * @param \Drupal\tome_sync\Event\ContentCrudEvent $event
   *   The event.
   */
  public function exportContent(ContentCrudEvent $event) {
    if ($event->getContent()->getEntityTypeId() === 'file') {
      return;
    }
    $has_file_field = FALSE;
    foreach ($event->getContent()->getFieldDefinitions() as $definition) {
      if (in_array($definition->getType(), ['file', 'image'], TRUE)) {
        $has_file_field = TRUE;
        break;
      }
    }
    if ($has_file_field) {
      $this->deleteUnusedFiles();
    }
  }

  /**
   * Reacts to config events.
   *
   * @param \Drupal\Core\Config\ConfigCrudEvent $event
   *   The event.
   */
  public function exportConfig(ConfigCrudEvent $event) {
    if (!\Drupal::isConfigSyncing() && !isset($GLOBALS['_tome_sync_installing'])) {
      $this->deleteUnusedFiles();
    }
  }

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    $events[TomeSyncEvents::EXPORT_CONTENT][] = ['exportContent', -1];
    $events[TomeSyncEvents::DELETE_CONTENT][] = ['exportContent', -1];
    $events[ConfigEvents::SAVE][] = ['exportConfig', -1];
    $events[ConfigEvents::DELETE][] = ['exportConfig', -1];
    return $events;
  }

}
