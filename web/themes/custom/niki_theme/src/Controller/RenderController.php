<?php

namespace Drupal\niki_theme\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 *
 */
class RenderController extends ControllerBase {

  /**
   * Render custom twig template.
   */
  public function renderMyCustomTwig(): array {
    return [
      '#theme' => 'my_first_theme_hook',
      '#title' => 'Niki Pench Template',
    ];
  }

}