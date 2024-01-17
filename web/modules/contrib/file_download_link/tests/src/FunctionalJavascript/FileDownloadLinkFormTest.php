<?php

namespace Drupal\Tests\file_download_link\FunctionalJavascript;

use Drupal\field\Entity\FieldConfig;
use Drupal\field\Entity\FieldStorageConfig;
use Drupal\FunctionalJavascriptTests\WebDriverTestBase;

/**
 * Tests the file_download_link form.
 *
 * @group file_download_link
 * @requires module token
 */
class FileDownloadLinkFormTest extends WebDriverTestBase {

  /**
   * Modules to enable.
   *
   * @var array
   */
  protected static $modules = [
    'field',
    'field_ui',
    'file',
    'image',
    'node',
    'user',
    'file_download_link',
  ];

  /**
   * {@inheritdoc}
   */
  protected $defaultTheme = 'stark';

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();

    $this->drupalCreateContentType([
      'name' => 'page_with_image',
      'type' => 'page_with_image',
    ]);
    $field_storage = FieldStorageConfig::create([
      'field_name' => 'field_image_test',
      'entity_type' => 'node',
      'type' => 'image',
    ]);
    $field_storage->save();
    $instance = FieldConfig::create([
      'field_storage' => $field_storage,
      'bundle' => 'page_with_image',
      'label' => 'Image Field',
    ]);
    $instance->save();
    $display = \Drupal::service('entity_display.repository')->getViewDisplay('node', 'page_with_image')
      ->setComponent('field_image_test', [
        'type' => 'file_download_link',
        'settings' => [],
      ]);
    $display->save();

    $this->drupalCreateContentType([
      'name' => 'page_with_file',
      'type' => 'page_with_file',
    ]);
    $field_storage = FieldStorageConfig::create([
      'field_name' => 'field_file_test',
      'entity_type' => 'node',
      'type' => 'file',
    ]);
    $field_storage->save();
    $instance = FieldConfig::create([
      'field_storage' => $field_storage,
      'bundle' => 'page_with_file',
      'label' => 'File Field',
    ]);
    $instance->save();
    $display = \Drupal::service('entity_display.repository')->getViewDisplay('node', 'page_with_file')
      ->setComponent('field_file_test', [
        'type' => 'file_download_link',
        'settings' => [],
      ]);
    $display->save();

    $admin_user = $this->drupalCreateUser([], NULL, TRUE);
    $this->drupalLogin($admin_user);
  }

  /**
   * Tests file_download_link form.
   */
  public function testFileDownloadLinkForm() {
    // @todo Figure out why JS tests started failing on drupal.org.
    // @see https://www.drupal.org/project/file_download_link/issues/3268442
    $this->markTestSkipped();
    $this->drupalGet('admin/structure/types/manage/page_with_image/display');
    $assert_session = $this->assertSession();
    $page = $this->getSession()->getPage();

    $fields = [
      'link_text' => 'fields[field_image_test][settings_edit_form][settings][link_text]',
      'new_tab' => 'fields[field_image_test][settings_edit_form][settings][new_tab]',
      'force_download' => 'fields[field_image_test][settings_edit_form][settings][force_download]',
      'link_title' => 'fields[field_image_test][settings_edit_form][settings][link_title]',
      'custom_classes' => 'fields[field_image_test][settings_edit_form][settings][custom_classes]',
    ];

    // Assert default summary.
    $assert_session->elementTextContains('css', '[data-drupal-selector="edit-fields-field-image-test"]', 'Link Text: Download');
    $assert_session->elementTextContains('css', '[data-drupal-selector="edit-fields-field-image-test"]', 'Open in new tab');
    $assert_session->elementTextContains('css', '[data-drupal-selector="edit-fields-field-image-test"]', 'Force download');
    $assert_session->elementTextNotContains('css', '[data-drupal-selector="edit-fields-field-image-test"]', 'Link Title');
    $assert_session->elementTextNotContains('css', '[data-drupal-selector="edit-fields-field-image-test"]', 'Classes');

    // Open the form and assert help text.
    $page->find('css', '#edit-fields-field-image-test-settings-edit')->click();
    $assert_session->waitForField($fields['link_text']);
    $assert_session->elementNotExists('css', '[data-drupal-selector="edit-fields-field-image-test-settings-edit-form-settings-token-example"]');
    $expected_message = 'Enable the token module to allow more flexible link text. For example, you would be able show the alt text followed by the file size like this:[node:field_image_test:alt] ([file:size])';
    $assert_session->elementTextContains('css', '[data-drupal-selector="edit-fields-field-image-test-settings-edit-form-settings-token-warning"]', $expected_message);
    // Change some settings.
    $page->findField($fields['link_text'])->setValue('Download Now!');
    $page->findField($fields['new_tab'])->setValue(FALSE);
    $page->findField($fields['force_download'])->setValue(TRUE);
    $page->findField($fields['link_title'])->setValue('Tooltip');
    $page->findField($fields['custom_classes'])->setValue('my-class your-class');
    $page->pressButton('Save');
    // Assert new summary text.
    $assert_session->elementTextContains('css', '[data-drupal-selector="edit-fields-field-image-test"]', 'Link Text: Download Now!');
    $assert_session->elementTextNotContains('css', '[data-drupal-selector="edit-fields-field-image-test"]', 'Open in new tab');
    $assert_session->elementTextContains('css', '[data-drupal-selector="edit-fields-field-image-test"]', 'Force download');
    $assert_session->elementTextContains('css', '[data-drupal-selector="edit-fields-field-image-test"]', 'Link Title: Tooltip');
    $assert_session->elementTextContains('css', '[data-drupal-selector="edit-fields-field-image-test"]', 'Classes: my-class your-class');

    // Save form and assert settings have been updated.
    $page->pressButton('Save');
    $assert_session->elementTextContains('css', '[data-drupal-selector="edit-fields-field-image-test"]', 'Link Text: Download Now!');
    $assert_session->elementTextNotContains('css', '[data-drupal-selector="edit-fields-field-image-test"]', 'Open in new tab');
    $assert_session->elementTextContains('css', '[data-drupal-selector="edit-fields-field-image-test"]', 'Force download');
    $assert_session->elementTextContains('css', '[data-drupal-selector="edit-fields-field-image-test"]', 'Link Title: Tooltip');
    $assert_session->elementTextContains('css', '[data-drupal-selector="edit-fields-field-image-test"]', 'Classes: my-class your-class');
    $expected_settings = [
      'link_text' => 'Download Now!',
      'link_title' => 'Tooltip',
      'new_tab' => FALSE,
      'force_download' => TRUE,
      'custom_classes' => 'my-class your-class',
    ];
    $settings = \Drupal::service('entity_display.repository')->getViewDisplay('node', 'page_with_image')
      ->getComponent('field_image_test')['settings'];
    $this->assertEqualsCanonicalizing($expected_settings, $settings);

    // Enable token module and check out help text.
    \Drupal::service('module_installer')->install(['token']);
    $page->find('css', '#edit-fields-field-image-test-settings-edit')->click();
    $assert_session->waitForField($fields['link_text']);
    $assert_session->elementNotExists('css', '[data-drupal-selector="edit-fields-field-image-test-settings-edit-form-settings-token-warning"]');
    $expected_message = 'You can show the alt text followed by the file size like this:[node:field_image_test:alt] ([file:size])';
    $assert_session->elementTextContains('css', '[data-drupal-selector="edit-fields-field-image-test-settings-edit-form-settings-token-example"]', $expected_message);
    $delta_message = 'Note that you do not need to indicate a delta value for the field_image_test token. The appropriate delta is used automatically.';
    $assert_session->elementTextNotContains('css', '[data-drupal-selector="edit-fields-field-image-test-settings-edit-form-settings-token-example"]', $delta_message);
    $page->pressButton('Save');

    // Change the cardinality on field_image_test and check help text.
    $field = FieldStorageConfig::loadByName('node', 'field_image_test');
    $field->setCardinality(-1);
    $field->save();
    $page->find('css', '#edit-fields-field-image-test-settings-edit')->click();
    $assert_session->waitForField($fields['link_text']);
    $assert_session->elementNotExists('css', '[data-drupal-selector="edit-fields-field-image-test-settings-edit-form-settings-token-warning"]');
    $expected_message = 'You can show the alt text followed by the file size like this:[node:field_image_test:alt] ([file:size])';
    $assert_session->elementTextContains('css', '[data-drupal-selector="edit-fields-field-image-test-settings-edit-form-settings-token-example"]', $expected_message);
    $delta_message = 'Note that you do not need to indicate a delta value for the field_image_test token. The appropriate delta is used automatically.';
    $assert_session->elementTextContains('css', '[data-drupal-selector="edit-fields-field-image-test-settings-edit-form-settings-token-example"]', $delta_message);

    // Test the example token on the file field.
    $this->drupalGet('admin/structure/types/manage/page_with_file/display');
    $page->find('css', '#edit-fields-field-file-test-settings-edit')->click();
    $assert_session->waitForField($fields['link_text']);
    $assert_session->elementNotExists('css', '[data-drupal-selector="edit-fields-field-file-test-settings-edit-form-settings-token-warning"]');
    $expected_message = 'You can show the file description followed by the file size like this:[node:field_file_test:description] ([file:size])';
    $assert_session->elementTextContains('css', '[data-drupal-selector="edit-fields-field-file-test-settings-edit-form-settings-token-example"]', $expected_message);
  }

}
