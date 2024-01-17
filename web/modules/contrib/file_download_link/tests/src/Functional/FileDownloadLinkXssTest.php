<?php

namespace Drupal\Tests\file_download_link\Functional;

use Drupal\file\Entity\File;
use Drupal\media\Entity\Media;
use Drupal\node\Entity\Node;
use Drupal\Tests\BrowserTestBase;
use Drupal\Tests\TestFileCreationTrait;

/**
 * Tests file_download_link xss protection.
 *
 * @group file_download_link
 * @requires module token
 */
class FileDownloadLinkXssTest extends BrowserTestBase {

  use TestFileCreationTrait;

  /**
   * Modules to enable.
   *
   * @var array
   */
  protected static $modules = [
    'file_download_link_multilingual_test',
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
    $admin_user = $this->drupalCreateUser([], NULL, TRUE);
    $this->drupalLogin($admin_user);
    $xss = "<script>alert('xss')</script>";

    $english_uri = $this::generateFile('my-english-text', 10, 10, 'text');
    $english_file = File::create([
      'uri' => $english_uri,
      'filename' => 'my-english-text',
    ]);
    $english_file->save();
    $french_uri = $this::generateFile('ma-texte-francaise', 10, 11, 'text');
    $french_file = File::create([
      'uri' => $french_uri,
      'filename' => 'ma-texte-francaise',
    ]);
    $french_file->save();

    $media = Media::create([
      'bundle' => 'test_media',
      'name' => $xss,
      'field_media_file' => $english_file->id(),
      'field_description' => $xss,
    ]);
    $media->addTranslation('fr', [
      'name' => $xss,
      'field_media_file' => $french_file->id(),
      'field_description' => $xss,
    ]);
    $media->save();
    // Create un-translated media.
    $media_un = Media::create([
      'bundle' => 'test_media',
      'name' => $xss,
      'field_media_file' => $english_file->id(),
      'field_description' => $xss,
    ]);
    $media_un->save();

    $test_files = $this->getTestFiles('image');
    $image1 = File::create([
      'uri' => $test_files[0]->uri,
      'filename' => 'image1',
    ]);
    $image1->save();
    $image2 = File::create([
      'uri' => $test_files[1]->uri,
      'filename' => 'image2',
    ]);
    $image2->save();

    // Create article referencing media and images.
    $node = Node::create([
      'type' => 'test_node',
      'title' => 'My Test Node',
      'field_media' => [$media->id(), $media_un->id()],
      'field_media_un' => [$media->id(), $media_un->id()],
      'field_image' => [
        [
          'target_id' => $image1->id(),
          'alt' => $xss,
        ],
        [
          'target_id' => $image2->id(),
          'alt' => $xss,
        ],
      ],
    ]);
    $node->addTranslation('fr', [
      'title' => $xss,
      'field_media' => [$media->id(), $media_un->id()],
      'field_image' => [
        [
          'target_id' => $image1->id(),
          'alt' => $xss,
        ],
        [
          'target_id' => $image2->id(),
          'alt' => $xss,
        ],
      ],
    ]);
    $node->save();
  }

  /**
   * Tests file_download_link xss prevention.
   */
  public function testFileDownloadLinkXss() {
    $this->drupalGet('node/1');
    $xss = "<script>alert('xss')</script>";
    $this->assertSession()->responseNotContains($xss);
    $this->drupalGet('fr/node/1');
    $this->assertSession()->responseNotContains($xss);
  }

}
