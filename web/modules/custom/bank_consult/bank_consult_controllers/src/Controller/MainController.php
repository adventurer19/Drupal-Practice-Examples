<?php

namespace Drupal\bank_consult_controllers\Controller;

use Drupal\bank_consult_advertiser\Entity\Advertiser;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Routing\RouteMatch;

class MainController extends ControllerBase {

  public function index($json_data_model,RouteMatch $route_match) {
    $label = 'undefined';
    return [
      '#markup' => $this->t(json_encode($json_data_model)),
    ];
  }

  public function upcastingWithEntityParam(Advertiser $advertiser): array {
    return [
      '#markup' => $this->t($advertiser->label()),
    ];
  }

  public function upcastingDemoWithValidation($niki_variable) {
    $text = $this->t('<br>Here is url value upcased by ParamConverter ,it must be Integer: <strong>%variable</strong> ', ['%variable' => $niki_variable]);
    return [
      '#markup' => $text,
    ];
  }

  public function upcastingDemo($niki_variable) {
    $text = $this->t('<br>Here is url value upcased by ParamConverter: <strong>%variable</strong> ', ['%variable' => $niki_variable]);
    return [
      '#markup' => $text,
    ];
  }

  public function delete(EntityInterface $entity) {
    try {
      //      $entity->delete();
      return $this->redirect('system.admin_content');
    }
    catch (\Exception $e) {
      // todo DI;
      \Drupal::logger('bank_consult_controllers')->error($e->getMessage());
    }
    return [
      '#markup' => $this->t('h1'),
    ];
  }

}