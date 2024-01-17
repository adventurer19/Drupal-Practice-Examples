<?php

/**
 * @file
 * Contains Drupal\bank_consult_queue\Plugin\QueueWorker\BankConsultQueueBasePlugin.php
 */

namespace Drupal\bank_consult_queue\Plugin\QueueWorker;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Queue\QueueWorkerBase;
use Drupal\node\NodeInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;


/**
 * Provides base functionality for the Bank Consult Queue Workers.
 */
abstract class BankConsultQueueBasePlugin extends QueueWorkerBase implements ContainerFactoryPluginInterface {

  /**
   * The term storage.
   *
   * @var \Drupal\Core\Entity\EntityStorageInterface
   */
  protected EntityStorageInterface $taxonomyStorage;

  /**
   * Creates a new NodePublishBase object.
   *
   * @param \Drupal\Core\Entity\EntityStorageInterface $storage
   *   The node storage.
   */
  public function __construct(EntityStorageInterface $storage) {
    $this->taxonomyStorage = $storage;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $container->get('entity.manager')->getStorage('taxonomy_term')
    );
  }

  /**
   * Publishes a term.
   *
   * @param \Drupal\taxonomy\TermInterface $term
   *
   * @return int
   */
  protected function publishNode($term) {
    $term->setPublished(TRUE);
    return $term->save();
  }

  /**
   * {@inheritdoc}
   */
  public function processItem($data) {
    /** @var NodeInterface $term */
    $term = $this->taxonomyStorage->load($data->nid);
    if (!$term->isPublished() && $term instanceof NodeInterface) {
//      return $this->publishNode($term);
    }
  }
}