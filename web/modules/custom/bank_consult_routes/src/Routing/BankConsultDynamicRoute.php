<?php

namespace Drupal\bank_consult_routes\Routing;

use Symfony\Component\Routing\Route;

class BankConsultDynamicRoute {

  public function getDynamicRoute(): array {
    $routes = [];
    $route['bank_consult_routes.dynamic_route'] = new Route(
      '/dynamic-route/first',
      [
        '_controller' => '\Drupal\bank_consult_routes\Controller\BankConsultOneController::index',
        'time' => \Drupal::time()->getCurrentTime(),
        '_title' => 'Hello Nikolay',
      ],
      [
        '_access' => 'TRUE',
      ],
    );
    return $route;
  }

}
