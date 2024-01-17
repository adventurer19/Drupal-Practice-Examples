<?php

namespace Drupal\tome_sync\Form;

use Drupal\Core\Config\StorageInterface;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\File\FileSystemInterface;
use Drupal\tome_base\PathTrait;
use Drupal\tome_sync\CleanFilesTrait;
use Drupal\tome_sync\ContentIndexerTrait;
use Drupal\tome_sync\FileSyncInterface;

/**
 * Contains a form for deleting unused files.
 *
 * @internal
 */
class CleanFilesForm extends FormBase {

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
   * Creates a CleanFilesForm object.
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
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('tome_sync.storage.content'),
      $container->get('config.storage.sync'),
      $container->get('tome_sync.file_sync'),
      $container->get('file_system'),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'tome_sync_clean_files_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $files = $this->getUnusedFiles();
    if (empty($files)) {
      \Drupal::messenger()->addStatus('No unused files found!');
      return $form;
    }

    $form['warning'] = [
      '#theme' => 'status_messages',
      '#message_list' => [
        'warning' => [t('The files below appear to be unused and will be deleted. This operation is permanent.')],
      ],
      '#status_headings' => ['warning' => t('Review list below before proceeding')],
    ];

    $form['file_list'] = [
      '#type' => 'html_tag',
      '#tag' => 'ul',
    ];
    asort($files);
    foreach ($files as $uuid => $filename) {
      $form['file_list'][] = [
        '#type' => 'html_tag',
        '#tag' => 'li',
        '#value' => "$filename ($uuid)",
      ];
    }

    $form['actions'] = [
      '#type' => 'actions',
    ];

    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => t('Delete @count files above', ['@count' => count($files)]),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $files = $this->getUnusedFiles();
    foreach ($files as $uuid => $filename) {
      $this->contentStorage->delete("file.$uuid");
      $this->unIndexContentByName("file.$uuid");
      $this->fileSync->deleteFile($filename);
    }
    \Drupal::messenger()->addMessage('All unused files have been deleted.');
  }

}
