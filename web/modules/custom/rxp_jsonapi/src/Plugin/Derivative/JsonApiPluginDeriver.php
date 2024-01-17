<?php

namespace Drupal\rxp_jsonapi\Plugin\Derivative;

use Drupal\Component\Plugin\Derivative\DeriverBase;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Plugin\Discovery\ContainerDeriverInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides block plugin definitions for custom blocks.
 */
class JsonApiPluginDeriver extends DeriverBase implements ContainerDeriverInterface {

  /**
   * @var EntityTypeManagerInterface $entityTypeManager.
   */
  protected $entityTypeManager;

  /**
   * The base plugin ID.
   *
   * @var string
   */
  protected $basePluginId;

  /**
   * Constructs a new JsonApiPluginDeriver.
   *
   * @param string $base_plugin_id
   *   The base plugin ID.
   */
  public function __construct($base_plugin_id, EntityTypeManagerInterface $entityTypeManager) {
    $this->entityTypeManager =$entityTypeManager;
    $this->basePluginId = $base_plugin_id;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, $base_plugin_id) {
    return new static(
      $base_plugin_id,
      $container->get('entity_type.manager')
    );
  }


  /**
   * {@inheritdoc}
   */
  public function getDerivativeDefinitions($base_plugin_definition): array {
        $entity_definitions = \Drupal::entityTypeManager()->getDefinitions();
        foreach ($entity_definitions as $entity_id => $definition) {
          $this->derivatives[$entity_id] = $base_plugin_definition;
          $this->derivatives[$entity_id]['id'] = $entity_id;
          $this->derivatives[$entity_id]['entity_type'] = $entity_id;
        }

    return parent::getDerivativeDefinitions($base_plugin_definition);
  }

}
