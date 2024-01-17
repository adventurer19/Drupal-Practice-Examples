<?php

namespace Drupal\rxp_forms\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Defines the Person Consult Settings Form.
 */
class PersonConsultSettingsForm extends FormBase {

  const PERSON_CONSULT_SETTINGS_NAME = 'person_consult_settings';

  /**
   * {@inheritDoc}
   */
  public function getFormId(): string {
    return self::PERSON_CONSULT_SETTINGS_NAME;
  }

  /**
   * {@inheritDoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state): array {
    return [
      '#markup' => 'Hi',
    ];
    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Submit'),
    ];

    return $form;
    // @todo Implement buildForm() method.
  }

  /**
   * {@inheritDoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // @todo Implement submitForm() method.
  }

  /**
   * @inheritDoc
   */
//  public static function create(ContainerInterface $container) {
//    return new static(
//      $container->get('messenger'),
//    );
//  }

  /**
   *
   */
//  public function __construct(protected $messenger) {
//
//  }

}
