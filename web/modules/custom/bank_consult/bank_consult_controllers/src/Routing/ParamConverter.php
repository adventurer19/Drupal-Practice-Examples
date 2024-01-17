<?php

namespace Drupal\bank_consult_controllers\Routing;

use Drupal\Core\ParamConverter\ParamConverterInterface;
use Drupal\bank_consult_controllers\Model\ApiDataModel;
use Symfony\Component\Routing\Route;

class ParamConverter implements ParamConverterInterface {

  /**
   * {@inheritdoc}
   */
  public function convert($value, $definition, $name, array $defaults) {
    // Example usage , usually we could have id and load the object from a particular storage.
    // But for the demo purposes we just load mocked object.
    $jsonDataFromApi = '{"id": 1, "name": "John Doe", "email": "john@example.com", "age": 25, "address": "123 Main St"}';

    try {
      return new ApiDataModel($jsonDataFromApi);
    }
    catch (\Exception $e) {
      //todo ... err message..
    }
    // return null in case where no object is found...
    return NULL;
  }

  /**
   * {@inheritdoc}
   */
  public function applies($definition, $name, Route $route) {
    return (!empty($definition['type']) && $definition['type'] == 'api_data_model');
  }

}
