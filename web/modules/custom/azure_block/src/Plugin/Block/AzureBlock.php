<?php

namespace Drupal\azure_block\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides a 'Azure Block' block.
 *
 * @Block(
 *   id = "azure_block",
 *   admin_label = @Translation("Azure block"),
 * )
 */
class AzureBlock extends BlockBase {

  public function build() {
    $config = $this->getConfiguration();

    $fax_number = $config['fax_number'] ?? '';

    return [
      '#markup' => $this->t('The fax number is @number!', ['@number' => $fax_number]),
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state): array {
    $form = parent::blockForm($form, $form_state);

    // Retrieve existing configuration for this block.
    $config = $this->getConfiguration();

    // Add a form field to the existing block configuration form.
    $form['fax_number'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Fax number'),
      '#default_value' => isset($config['fax_number']) ? $config['fax_number'] : '',
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    // Save our custom settings when the form is submitted.
    $this->setConfigurationValue('fax_number', $form_state->getValue('fax_number'));
  }

  /**
   * {@inheritdoc}
   */
  public function blockValidate($form, FormStateInterface $form_state) {
    $fax_number = $form_state->getValue('fax_number');
    if (!is_numeric($fax_number)) {
      $form_state->setErrorByName('fax_number', t('Needs to be an integer'));
    }
  }

}