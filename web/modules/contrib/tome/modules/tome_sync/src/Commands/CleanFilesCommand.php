<?php

namespace Drupal\tome_sync\Commands;

use Drupal\tome_base\PathTrait;
use Drupal\tome_base\CommandBase;
use Drupal\tome_sync\FileSyncInterface;
use Drupal\Core\Config\StorageInterface;
use Drupal\Core\File\FileSystemInterface;
use Drupal\tome_sync\CleanFilesTrait;
use Drupal\tome_sync\ContentIndexerTrait;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Contains the tome:clean-files command.
 *
 * @internal
 */
class CleanFilesCommand extends CommandBase {

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
   * Creates a CleanFilesCommand object.
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
    parent::__construct();
    $this->contentStorage = $content_storage;
    $this->configStorage = $config_storage;
    $this->fileSync = $file_sync;
    $this->fileSystem = $file_system;
  }

  /**
   * {@inheritdoc}
   */
  protected function configure() {
    $this->setName('tome:clean-files')
      ->setDescription('Deletes unused files.')
      ->addOption('yes', 'y', InputOption::VALUE_NONE, 'Delete all files without a yes/no prompt.');
  }

  /**
   * {@inheritdoc}
   */
  protected function execute(InputInterface $input, OutputInterface $output): int {
    $options = $input->getOptions();

    $this->io()->writeLn('Searching for unused files...');
    $files = $this->getUnusedFiles();
    if (empty($files)) {
      $this->io()->success('No unused files found.');
      return 0;
    }
    $this->io()->listing($files);
    if (!$options['yes'] && !$this->io()->confirm('The files listed above will be deleted.', FALSE)) {
      return 0;
    }
    foreach ($files as $uuid => $filename) {
      $this->contentStorage->delete("file.$uuid");
      $this->unIndexContentByName("file.$uuid");
      $this->fileSync->deleteFile($filename);
    }
    $this->io()->success('Deleted all unused files.');
    return 0;
  }

}
