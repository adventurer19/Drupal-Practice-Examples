<?php

namespace Drupal\bank_consult_controller\Controller;

use Drupal\Core\Controller\ControllerBase;

class BankConsultController extends ControllerBase {

  public function index() {
    return [
      '#markup' => 'Hello world !',
    ];
  }

}
