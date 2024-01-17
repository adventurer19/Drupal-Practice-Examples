<?php

/**
 * @file
 * Contains \Drupal\bank_consult_queue\QueueHandler.php
 */

namespace Drupal\bank_consult_queue;

use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class QueueHandler implements ContainerInjectionInterface {

  protected $term_storage;

  public function process(EntityInterface $entity) {

    if ($entity->getEntityTypeId() !== 'taxonomy_term') {
      return;
    }
//
//    if ($entity->isPublished()) {
//      return;
//    }

    $queue_factory = \Drupal::service('queue');
    $queue = $queue_factory->get('cron_node_publisher');
    $item = new \stdClass();
    $item->nid = $entity->id();
    $queue->createItem($item);
  }

  public function __construct(EntityTypeManagerInterface $entity_type_manager) {
    $this->entityTypeManager = $entity_type_manager;
    $this->term_storage = $this->entityTypeManager->getStorage('taxonomy_term');
  }

  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity_type.manager')
    );
  }

}