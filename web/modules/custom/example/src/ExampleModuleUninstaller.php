<?php

namespace Drupal\example;

use Drupal\Core\Extension\ModuleUninstallValidatorInterface;

class ExampleModuleUninstaller implements ModuleUninstallValidatorInterface {

  public function validate($module) {
//    if ($module === 'example') {
//      return [\Drupal::translation()->translate('NYou cannot uninstall this module.')];
//    }
    // TODO: Implement validate() method.
  }


}
