<?php

namespace Drupal\azure_twig\Utils;

class AzureHelper {

  public function printHello(): array {
    return [
      '#markup' => t('Hello world from my Azure Helper.'),
    ];
  }

  public function printYourName(string $name): array {
    return [
      '#markup' => t('Your name is %name', ['%name' => $name]),
    ];
  }

}