<?php

namespace Drupal\rxp_install\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * RXP INSTALL FORM CLASS.
 */
class RXPInstallForm extends FormBase {

  public const RXP_INSTALL_FORM_ID = 'rxp_install_form';

  /**
   * {@inheritDoc}
   */
  public function getFormId() {
    return self::RXP_INSTALL_FORM_ID;
    // @todo Implement getFormId() method.
  }

  /**
   * {@inheritDoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    return [
      '#markup' => $this->t('Hi my name is Niki.'),
    ];
    // @todo Implement buildForm() method.
  }

  /**
   * {@inheritDoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // @todo Implement submitForm() method.
  }

}
