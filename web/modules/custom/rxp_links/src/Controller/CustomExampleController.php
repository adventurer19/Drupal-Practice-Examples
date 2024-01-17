<?php

namespace Drupal\rxp_links\Controller;

use Drupal\Core\Controller\ControllerBase;

class CustomExampleController extends ControllerBase {


  public function route1() {
    return [
      '#markup' => 'hi',
    ];
  }

  public function route2() {
    return [
      '#markup' => 'hi two',
    ];
  }

}