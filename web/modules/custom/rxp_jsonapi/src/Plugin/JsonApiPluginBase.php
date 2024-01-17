<?php

namespace Drupal\rxp_jsonapi\Plugin;

use Drupal\Component\Plugin\PluginBase;
use Drupal\rxp_jsonapi\JsonApiPluginInterface;
use Symfony\Component\Routing\Route;

/**
 * Base class for JSON API plugins.
 */
abstract class JsonApiPluginBase extends PluginBase implements JsonApiPluginInterface {

  /**
   * The plugin ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The plugin name.
   *
   * @var string
   */
  protected $name;

  /**
   * Constructs a new JsonApiPluginBase object.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin ID for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $annotation = $this->getPluginDefinition();
    $this->id = $annotation['id'];
    $this->name = $annotation['name'];
  }

  public function getPluginEntityType() {
    return $this->getPluginDefinition()['entity_type'];
  }

  /**
   * {@inheritdoc}
   */
  public function getId() {
    return $this->id;
  }

  /**
   * {@inheritdoc}
   */
  public function getName() {
    return $this->name;
  }

  /**
   * {@inheritdoc}
   */
  public function process() {
    // Implement processing logic in specific plugins.
  }


  public function getRoute(string $entity_id) {
    $routes['example.content'] = new Route(
      "/jsonapi/$entity_id",
      [
        '_controller' => '\Drupal\rxp_jsonapi\Controller\JsonApiController::processData',
        '_title' => 'Json API' .  $entity_id,
      ],
      [
        '_permission'  => 'access content',
      ]
    );
  }



}
