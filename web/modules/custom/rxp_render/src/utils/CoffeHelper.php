<?php

namespace Drupal\rxp_render\utils;

class CoffeHelper {

  /**
   * Just render arbitrary text .
   */
  public function renderCoffer() {
    return [
      '#markup' => 'this is your coffe here.',
    ];
  }

  public function sayHello() {
    return [
      '#markup' => 'Hello world!',
    ];

}

}