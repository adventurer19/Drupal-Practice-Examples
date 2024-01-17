<?php

namespace Drupal\route_options\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\Core\Session\AccountInterface;
use Psr\Http\Message\ServerRequestInterface;
use Symfony\Component\HttpFoundation\Request;

class DummyForm extends FormBase {

  public function getFormId() {
    return 'dummy_form';
  }

  public function buildForm(array $form, FormStateInterface $form_state,
                            AccountInterface $user = NULL,
                            Request $request = NULL,
                            RouteMatchInterface $routeMatch = null,
                            ServerRequestInterface $serverRequest = null) {
  }

  public function submitForm(array &$form, FormStateInterface $form_state) {
  }

}
