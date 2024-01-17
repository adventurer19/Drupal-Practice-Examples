<?php

namespace Drupal\bank_consult\Form;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Language\LanguageInterface;
use Drupal\Core\Language\LanguageManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class BankConsultPersonSettingsForm extends ConfigFormBase {

  const BANK_CONSULT_PERSON_SETTINGS_NAME = 'bank_consult.person_settings';

  const BANK_CONSULT_PERSON_SETTING_FORM_ID = 'bank_consult.person_setting_form';


  /**
   * The language manager.
   *
   * @var \Drupal\Core\Language\LanguageManagerInterface
   */
  protected LanguageManagerInterface $languageManager;

  protected LanguageInterface $currentLanguage;


  /**
   * @inheritDoc
   */
  protected function getEditableConfigNames() : array {
    return [self::BANK_CONSULT_PERSON_SETTINGS_NAME];
  }

  /**
   * @inheritDoc
   */
  public function getFormId() : string {
    return self::BANK_CONSULT_PERSON_SETTING_FORM_ID;
  }

  /**
   * @inheritDoc
   */
  public function buildForm(array $form, FormStateInterface $form_state): array {
    // Load the immutable config.
    $this->languageManager->setConfigOverrideLanguage($this->currentLanguage);
    $config = \Drupal::config(self::BANK_CONSULT_PERSON_SETTINGS_NAME);
//    $translation_config = $this->configFactory()->get($name)->get();
//    $this->configFactory->get(self::BANK_CONSULT_PERSON_SETTINGS_NAME)->get();

//    $config = $this->config(self::BANK_CONSULT_PERSON_SETTINGS_NAME)->get();
    // Set configuration values based on form submission and source values.
//    $base_config = $this->configFactory()->getEditable($name);
    $this->languageManager->getLanguageConfigOverride('bg',self::BANK_CONSULT_PERSON_SETTINGS_NAME);
//    $config_translation = $this->languageManager->getLanguageConfigOverride($this->language->getId(), $name);
    $form['consultant_name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Consultant Name'),
      '#description' => $this->t('Enter the name of the bank consultant.'),
      '#default_value' => $config->get('consultant_name'),
    ];

    $form['consultant_email'] = [
      '#type' => 'email',
      '#title' => $this->t('Consultant Email'),
      '#description' => $this->t('Enter the email address of the bank consultant.'),
      '#default_value' => $config->get('consultant_email'),
    ];

    $form['consultant_id'] = [
      '#type' => 'number',
      '#title' => $this->t('Consultant ID'),
      '#description' => $this->t('Enter the ID of the bank consultant.'),
      '#default_value' => $config->get('consultant_id'),
    ];

    $form['additional_notes'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Additional Notes'),
      '#description' => $this->t('Add any additional notes or comments about the consultant.'),
      '#default_value' => $config->get('additional_notes'),

    ];

    $form['enabled'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Enabled'),
      '#description' => $this->t('Check to enable the bank consultant.'),
      '#default_value' => $config->get('enabled'),

    ];
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $config = $this->configFactory->getEditable(self::BANK_CONSULT_PERSON_SETTINGS_NAME);
    foreach (['consultant_name', 'consultant_email', 'consultant_id', 'additional_notes', 'enabled'] as $key) {
      $config->set($key, $form_state->getValue($key));
    }
//    $immutable = \Drupal::config(self::BANK_CONSULT_PERSON_SETTINGS_NAME);
    $config->save();
    parent::submitForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    $instance = parent::create($container);
    $instance->languageManager = $container->get('language_manager');
    $instance->currentLanguage = $instance->languageManager->getCurrentLanguage();
    return $instance;
  }

}
