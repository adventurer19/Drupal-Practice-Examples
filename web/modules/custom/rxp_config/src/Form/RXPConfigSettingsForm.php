<?php

namespace Drupal\rxp_config\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * RXP config form.
 */
class RXPConfigSettingsForm extends ConfigFormBase {

  /**
   * {@inheritDoc}
   */
  protected function getEditableConfigNames(): array {
    return ['rxp_config.settings'];
  }

  /**
   * {@inheritDoc}
   */
  public function getFormId(): string {
    return 'rxp_config_settings_form';
  }

  /**
   * {@inheritDoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state): array {
    $config = $this->config('rxp_config.settings');

    $form['site_slogan'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Site Slogan'),
      '#default_value' => $config->get('site_slogan') ?? '',
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritDoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state): void {
    $values = $form_state->getValues();
    $this->config('rxp_config.settings')
      ->set('site_slogan', $values['site_slogan'])
      ->save();

    parent::submitForm($form, $form_state);
  }

}
