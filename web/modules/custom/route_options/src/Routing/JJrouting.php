<?php

/**
 * @file
 * Routing file defining dynamic routes.
 *
 */

namespace Drupal\route_options\Routing;

use Drupal\route_options\Form\DummyForm;
use Symfony\Component\Routing\Route;

class JJrouting {


  public function routes(): array {
    $routes = [];
    $routes['route_options.view'] = new Route(
      '/route-options/view/{user}',
      [
        '_form' => DummyForm::class,
        '_title' => 'DummyForm',
      ],
      [
        '_access' => 'TRUE',
      ],
      [
        'parameters' => [
          'user' => [
            'type' => 'entity:user',
          ],
        ],
      ]
    );
    return $routes;
  }

}

