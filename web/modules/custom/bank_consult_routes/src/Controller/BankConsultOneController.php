<?php

namespace Drupal\bank_consult_routes\Controller;

use Drupal\Core\Controller\ControllerBase;

class BankConsultOneController extends ControllerBase {

  public function index($time) {
    return [
      '#markup' => "The time is <h1>$time</h1>",
    ];
  }
}
