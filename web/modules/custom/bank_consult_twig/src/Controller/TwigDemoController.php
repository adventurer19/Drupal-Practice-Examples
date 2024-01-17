<?php

namespace Drupal\bank_consult_twig\Controller;


use Drupal\Core\Controller\ControllerBase;

class TwigDemoController extends ControllerBase {

  public function index($custom_arg) {
    $build['data'] = [
      '#theme' => 'bank_consult_demo_first'
    ];
    $build['#markup'] = $this->t('Hi');
    return $build;

  }

}
