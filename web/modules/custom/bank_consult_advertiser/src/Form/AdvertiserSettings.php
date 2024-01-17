<?php

namespace Drupal\bank_consult_advertiser\Form;

use Drupal\Component\Utility\EmailValidatorInterface;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class AdvertiserSettings extends ConfigFormBase {

  const SETTINGS = 'advertiser.settings';

  /**
   * The email validator service.
   *
   * @var \Drupal\Component\Utility\EmailValidatorInterface
   */
  protected EmailValidatorInterface $emailValidator;

  public static function create(ContainerInterface $container) {
    $instance = parent::create($container);
    $instance->emailValidator = $container->get('email.validator');
    return $instance;
  }

  public function buildForm(array $form, FormStateInterface $form_state): array {
    $config = $this->config(self::SETTINGS);

    $form['emails'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Emails'),
      '#description' => $this->t('Provide emails with comma separated values'),
      '#default_value' => implode(',', $config->get('emails')),
    ];
    return parent::buildForm($form, $form_state);
  }

  protected function getEditableConfigNames(): array {
    return [
      self::SETTINGS,
    ];
  }

  public function validateForm(array &$form, FormStateInterface $form_state): void {
    $emails = $this->processEmails($form_state);
    foreach ($emails as $email) {
      if (!$this->emailValidator->isValid($email)) {
        $form_state->setError($form['emails'], $this->t('This email %value is invalid', [
          '%value' => $email,
        ]));
        break;
      }
    }
  }

  public function getFormId(): string {
    return 'bank_consult_advertiser_config_form';
  }

  public function submitForm(array &$form, FormStateInterface $form_state): void {
    $emails = explode(',', $form_state->getValue('emails'));
    $this->config(self::SETTINGS)
      ->set('emails', $emails)
      ->save();

    parent::submitForm($form, $form_state);
  }

  private function processEmails(FormStateInterface $form_state): array {
    return explode(',', $form_state->getValue('emails', []));
  }

}