<?php

namespace Drupal\my_module\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Defines the configuration form for the mymodule module.
 */
class MySettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['my_module.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'mymodule_config_form';
  }


  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('my_module.settings');
    $config_two = \Drupal::config('my_module.settings');

    $form['enabled'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Enable feature'),
      '#default_value' => $config->get('enabled'),
    ];

    $form['threshold'] = [
      '#type' => 'number',
      '#title' => $this->t('Threshold'),
      '#default_value' => $config->get('threshold'),
    ];

    $form['message'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Custom Message'),
      '#default_value' => $config->get('message'),
    ];
    if ($override_value = $this->configFactory->get('my_module.settings')->get('message')) {
      $form['override']['message'] = [
        '#type' => 'textfield',
        '#title' => $this->t('Custom Message Override'),
        '#default_value' => $override_value,
      ];
    }

    $form['max_items'] = [
      '#type' => 'number',
      '#title' => $this->t('Maximum Items'),
      '#default_value' => $config->get('max_items'),
    ];

    $form['allowed_roles'] = [
      '#type' => 'checkboxes',
      '#title' => $this->t('Allowed Roles'),
      '#options' => user_role_names(),
      '#default_value' => $config->get('allowed_roles'),
    ];

    $form['default_color'] = [
      '#type' => 'color',
      '#title' => $this->t('Default Color'),
      '#default_value' => $config->get('default_color'),
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $editable_config = $this->configFactory->getEditable('mymodule.settings');
//    $config = $this->config('mymodule.settings');
    $editable_config
      ->set('enabled', $form_state->getValue('enabled'))
      ->set('threshold', $form_state->getValue('threshold'))
      ->set('message', $form_state->getValue('message'))
      ->set('max_items', $form_state->getValue('max_items'))
      ->set('allowed_roles', $form_state->getValue('allowed_roles'))
      ->set('default_color', $form_state->getValue('default_color'))
      ->save();

    parent::submitForm($form, $form_state);
  }

}
