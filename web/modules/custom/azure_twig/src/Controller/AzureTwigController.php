<?php

namespace Drupal\azure_twig\Controller;

use Drupal\Core\Controller\ControllerBase;

class  AzureTwigController extends ControllerBase {

  public function content() {
    return [
      'first_item' => [
        '#plain_text' => $this->t('Hello world'),
      ],
      'second_item' => [
        '#theme' => 'azure_twig_block',
        '#age' => 19,
        '#name' => 'Nikolay',
      ],
    ];
  }

}