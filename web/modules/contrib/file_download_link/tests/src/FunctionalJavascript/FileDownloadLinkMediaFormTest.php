<?php

namespace Drupal\Tests\file_download_link\FunctionalJavascript;

use Drupal\field\Entity\FieldConfig;
use Drupal\field\Entity\FieldStorageConfig;
use Drupal\FunctionalJavascriptTests\WebDriverTestBase;
use Drupal\Tests\media\Traits\MediaTypeCreationTrait;

/**
 * Tests the file_download_link and file_download_link_media forms.
 *
 * @group file_download_link
 * @requires module token
 */
class FileDownloadLinkMediaFormTest extends WebDriverTestBase {

  use MediaTypeCreationTrait;

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
    'media',
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

    $this->createMediaType('file', ['id' => 'file', 'label' => 'file']);

    $this->drupalCreateContentType([
      'name' => 'page_with_media',
      'type' => 'page_with_media',
    ]);
    $field_storage = FieldStorageConfig::create([
      'field_name' => 'field_media_test',
      'entity_type' => 'node',
      'type' => 'entity_reference',
      'settings' => [
        'target_type' => 'media',
      ],
    ]);
    $field_storage->save();
    $instance = FieldConfig::create([
      'field_storage' => $field_storage,
      'bundle' => 'page_with_media',
      'label' => 'Media Field',
      'settings' => [
        'handler' => 'default:media',
        'handler_settings' => [
          'target_bundles' => [
            'file' => 'file',
          ],
        ],
      ],
    ]);
    $instance->save();

    $display = \Drupal::service('entity_display.repository')->getViewDisplay('media', 'file')
      ->setComponent('field_media_file', [
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
    $this->drupalGet('admin/structure/media/manage/file/display');
    $assert_session = $this->assertSession();
    $page = $this->getSession()->getPage();

    $fields = [
      'link_text' => 'fields[field_media_file][settings_edit_form][settings][link_text]',
      'new_tab' => 'fields[field_media_file][settings_edit_form][settings][new_tab]',
      'force_download' => 'fields[field_media_file][settings_edit_form][settings][force_download]',
      'link_title' => 'fields[field_media_file][settings_edit_form][settings][link_title]',
      'custom_classes' => 'fields[field_media_file][settings_edit_form][settings][custom_classes]',
    ];

    // Open the form and assert help text.
    $page->find('css', '#edit-fields-field-media-file-settings-edit')->click();
    $assert_session->waitForField($fields['link_text']);
    // Token example.
    $assert_session->elementNotExists('css', '[data-drupal-selector="edit-fields-field-media-file-settings-edit-form-settings-token-example"]');
    // Token warning.
    $expected_message = 'Enable the token module to allow more flexible link text. For example, you would be able show the file description followed by the file size like this:[media:field_media_file:description] ([file:size])';
    $assert_session->elementTextContains('css', '[data-drupal-selector="edit-fields-field-media-file-settings-edit-form-settings-token-warning"]', $expected_message);
    // Media warning.
    $expected_message = "Did you know the file_download_link_media module allows you render a Media reference field as a link to the Media's source file or image? Consider enabling the module if that sounds helpful.";
    $assert_session->elementTextContains('css', '[data-drupal-selector="edit-fields-field-media-file-settings-edit-form-settings-media-warning"]', $expected_message);
    $page->pressButton('Save');

    // Enable modules and check warnings again.
    \Drupal::service('module_installer')->install(['token', 'file_download_link_media']);
    $page->find('css', '#edit-fields-field-media-file-settings-edit')->click();
    $assert_session->waitForField($fields['link_text']);
    // Token example.
    $expected_message = 'You can show the file description followed by the file size like this:[media:field_media_file:description] ([file:size])';
    $assert_session->elementTextContains('css', '[data-drupal-selector="edit-fields-field-media-file-settings-edit-form-settings-token-example"]', $expected_message);
    // Token warning.
    $assert_session->elementNotExists('css', '[data-drupal-selector="edit-fields-field-media-file-settings-edit-form-settings-token-warning"]');
    // Media warning.
    $assert_session->elementNotExists('css', '[data-drupal-selector="edit-fields-field-media-file-settings-edit-form-settings-media-warning"]');

    // Go to node edit form and test file_download_link_media.
    $display = \Drupal::service('entity_display.repository')->getViewDisplay('node', 'page_with_media')
      ->setComponent('field_media_test', [
        'type' => 'file_download_link_media',
        'settings' => [],
      ]);
    $display->save();
    \Drupal::service('module_installer')->uninstall(['token']);
    $this->drupalGet('admin/structure/types/manage/page_with_media/display');

    $fields = [
      'link_text' => 'fields[field_media_test][settings_edit_form][settings][link_text]',
      'new_tab' => 'fields[field_media_test][settings_edit_form][settings][new_tab]',
      'force_download' => 'fields[field_media_test][settings_edit_form][settings][force_download]',
      'link_title' => 'fields[field_media_test][settings_edit_form][settings][link_title]',
      'custom_classes' => 'fields[field_media_test][settings_edit_form][settings][custom_classes]',
    ];

    // Assert default summary.
    $assert_session->elementTextContains('css', '[data-drupal-selector="edit-fields-field-media-test"]', 'Link Text: Download');
    $assert_session->elementTextContains('css', '[data-drupal-selector="edit-fields-field-media-test"]', 'Open in new tab');
    $assert_session->elementTextContains('css', '[data-drupal-selector="edit-fields-field-media-test"]', 'Force download');
    $assert_session->elementTextNotContains('css', '[data-drupal-selector="edit-fields-field-media-test"]', 'Link Title');
    $assert_session->elementTextNotContains('css', '[data-drupal-selector="edit-fields-field-media-test"]', 'Classes');

    // Open the form and assert help text.
    $page->find('css', '#edit-fields-field-media-test-settings-edit')->click();
    $assert_session->waitForField($fields['link_text']);
    $assert_session->elementNotExists('css', '[data-drupal-selector="edit-fields-field-media-test-settings-edit-form-settings-token-example"]');
    $expected_message = 'Enable the token module to allow more flexible link text. For example, you would be able show the media name followed by the file size like this[media:name] ([file:size])';
    $assert_session->elementTextContains('css', '[data-drupal-selector="edit-fields-field-media-test-settings-edit-form-settings-token-warning"]', $expected_message);
    // Change some settings.
    $page->findField($fields['link_text'])->setValue('Download Now!');
    $page->findField($fields['new_tab'])->setValue(FALSE);
    $page->findField($fields['force_download'])->setValue(TRUE);
    $page->findField($fields['link_title'])->setValue('Tooltip');
    $page->findField($fields['custom_classes'])->setValue('my-class your-class');
    $page->pressButton('Save');
    // Assert new summary text.
    $assert_session->elementTextContains('css', '[data-drupal-selector="edit-fields-field-media-test"]', 'Link Text: Download Now!');
    $assert_session->elementTextNotContains('css', '[data-drupal-selector="edit-fields-field-media-test"]', 'Open in new tab');
    $assert_session->elementTextContains('css', '[data-drupal-selector="edit-fields-field-media-test"]', 'Force download');
    $assert_session->elementTextContains('css', '[data-drupal-selector="edit-fields-field-media-test"]', 'Link Title: Tooltip');
    $assert_session->elementTextContains('css', '[data-drupal-selector="edit-fields-field-media-test"]', 'Classes: my-class your-class');

    // Save form and assert settings have been updated.
    $page->pressButton('Save');
    $assert_session->elementTextContains('css', '[data-drupal-selector="edit-fields-field-media-test"]', 'Link Text: Download Now!');
    $assert_session->elementTextNotContains('css', '[data-drupal-selector="edit-fields-field-media-test"]', 'Open in new tab');
    $assert_session->elementTextContains('css', '[data-drupal-selector="edit-fields-field-media-test"]', 'Force download');
    $assert_session->elementTextContains('css', '[data-drupal-selector="edit-fields-field-media-test"]', 'Link Title: Tooltip');
    $assert_session->elementTextContains('css', '[data-drupal-selector="edit-fields-field-media-test"]', 'Classes: my-class your-class');
    $expected_settings = [
      'link_text' => 'Download Now!',
      'link_title' => 'Tooltip',
      'new_tab' => FALSE,
      'force_download' => TRUE,
      'custom_classes' => 'my-class your-class',
    ];
    $settings = \Drupal::service('entity_display.repository')->getViewDisplay('node', 'page_with_media')
      ->getComponent('field_media_test')['settings'];
    $this->assertEqualsCanonicalizing($expected_settings, $settings);

    // Enable token module and check out help text.
    \Drupal::service('module_installer')->install(['token']);
    $page->find('css', '#edit-fields-field-media-test-settings-edit')->click();
    $assert_session->waitForField($fields['link_text']);
    $assert_session->elementNotExists('css', '[data-drupal-selector="edit-fields-field-media-test-settings-edit-form-settings-token-warning"]');
    $expected_message = 'You can show the media name followed by the file size like this:[media:name] ([file:size])';
    $assert_session->elementTextContains('css', '[data-drupal-selector="edit-fields-field-media-test-settings-edit-form-settings-token-example"]', $expected_message);
    $delta_message = 'Note that you do not need to indicate a delta value for the field_media_test token. The appropriate delta is used automatically.';
    $assert_session->elementTextNotContains('css', '[data-drupal-selector="edit-fields-field-media-test-settings-edit-form-settings-token-example"]', $delta_message);
  }

}
