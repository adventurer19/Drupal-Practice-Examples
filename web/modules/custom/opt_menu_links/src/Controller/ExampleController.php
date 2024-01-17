<?php

namespace Drupal\opt_menu_links\Controller;

use Drupal\Component\Utility\Variable;
use Drupal\Core\Controller\ControllerBase;

/**
 * An example controller.
 */
class ExampleController extends ControllerBase {

  /**
   * Returns a render-able array for a test page.
   */
  public function content() {
    $y = 1;
    $fn1 = fn($x) => $x + $y;
    $fn1(1);
    $config = $this->config('system.site');
    $config = json_encode($config->getRawData());
    $build = [
      '#markup' => $config,
    ];
    return $build;
  }

}
