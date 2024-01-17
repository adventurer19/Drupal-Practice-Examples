<?php

namespace Drupal\rxp_jsonapi\Plugin\JsonApi;

use Drupal\Core\Routing\RouteMatch;
use Drupal\rxp_jsonapi\Plugin\JsonApiPluginBase;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

/**
 * Defines a JSON API plugin.
 *
 * @JsonApiExtension(
 *   id = "json_api_primary",
 *   name = "Example JSON API Plugin",
 *   deriver = "Drupal\rxp_jsonapi\Plugin\Derivative\JsonApiPluginDeriver"
 * )
 */
class JsonApiPlugin extends JsonApiPluginBase {

  public function processRoute(string $url_prefix = '/jsonapi/') {
    $entity_definition =  $this->getPluginEntityType();
    $route_path = $url_prefix. $entity_definition;
    $route = new Route(
      $route_path,
      [
        '_controller' => '\Drupal\rxp_jsonapi\Controller\JsonApiController::processData',
        '_title' => 'JSON NEW',
      ],
      [
        '_permission' => 'access content',
      ],
    );
    return [
      'route_name' => 'rxp_jsonapi.' . $entity_definition . '_collection',
      'route_object' =>  $route,
    ];
  }

  public function processRoutes() {
    $route_collection = new RouteCollection();
    $prefix = '/jsonapi-new/';
    $definitions = \Drupal::entityTypeManager()->getDefinitions();

    foreach ($definitions as $id => $definition) {
      $route =

      $route_name = 'rxp_jsonapi.' . $id . '_collection';
      $route_collection->add($route_name, $route);
    }

    return $route_collection;
  }

  public function buildLinks() {}

}