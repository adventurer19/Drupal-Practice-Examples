<?php

namespace Drupal\route_options\Controller;

use Drupal\Core\Controller\ControllerBase;

class RouteOptionsController extends ControllerBase {


  public function index($nikolay) {
    $build = [];
    $controller_name = self::class;
    $build['front_item'] = [
      '#markup' => $this->t("Hello world from $controller_name."),
    ];
    return $build;

  }
}
