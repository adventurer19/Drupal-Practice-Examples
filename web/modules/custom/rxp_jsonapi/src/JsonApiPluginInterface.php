<?php

namespace Drupal\rxp_jsonapi;

use Drupal\Component\Plugin\PluginInspectionInterface;

interface JsonApiPluginInterface extends PluginInspectionInterface {

  public function getRoute(string $entity_id);

}
