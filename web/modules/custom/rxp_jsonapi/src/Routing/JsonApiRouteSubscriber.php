<?php

namespace Drupal\rxp_jsonapi\Routing;

use Drupal\Core\Routing\RouteSubscriberBase;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

class JsonApiRouteSubscriber extends RouteSubscriberBase {

  public function routes() {
    $route_collection = new RouteCollection();
    $plugin_manager = \Drupal::service('plugin.manager.jsonapi');
    foreach ($plugin_manager->getDefinitions() as $id => $plugin) {
      /** @var \Drupal\rxp_jsonapi\Plugin\JsonApi\JsonApiPlugin $instance */
      $instance = $plugin_manager->createInstance($id);
      ['route_name' => $route_name, 'route_object' => $route_object] = $instance->processRoute('/jsonapi-new/');
        $route_collection->add($route_name,$route_object);
    }
    return $route_collection;

  }

  protected function alterRoutes(RouteCollection $collection) {
    $collection->add('rxp_jsonapi.json_api.index', new Route(
      '/jsonapi-new', [
      '_controller' => '\Drupal\rxp_jsonapi\Controller\JsonApiController::index',
      '_title' => 'TODO MAIN INDEX',
    ],
      [
        '_permission' => 'access content',
      ]
    ));
  }

}