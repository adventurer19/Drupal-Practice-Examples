<?php

namespace Drupal\Tests\file_download_link\Functional;

use Drupal\file\Entity\File;
use Drupal\media\Entity\Media;
use Drupal\node\Entity\Node;
use Drupal\Tests\BrowserTestBase;
use Drupal\Tests\TestFileCreationTrait;

/**
 * Tests file_download_link with translations.
 *
 * @group file_download_link
 * @requires module token
 */
class FileDownloadLinkMultilingualTest extends BrowserTestBase {

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

    // Create translated media. Having one translated and one
    // untranslated may help detect funkiness. We also have
    // one translated and one untranslated media field to detect
    // said funkiness.
    $media = Media::create([
      'bundle' => 'test_media',
      'name' => 'My Media',
      'field_media_file' => $english_file->id(),
      'field_description' => 'This is an English description',
    ]);
    $media->addTranslation('fr', [
      'name' => 'Mon Media',
      'field_media_file' => $french_file->id(),
      'field_description' => 'Les mots françaises.',
    ]);
    $media->save();
    // Create un-translated media.
    $media_un = Media::create([
      'bundle' => 'test_media',
      'name' => 'My Untranslated Media',
      'field_media_file' => $english_file->id(),
      'field_description' => 'This is untranslated',
    ]);
    $media_un->save();

    // Make images. Neither image itself is translated. The image
    // field is translated. Having two images is mostly just for
    // the heck of it and to show that the delta token magic works
    // in a functional setting.
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
          'alt' => 'This is image 1',
        ],
        [
          'target_id' => $image2->id(),
          'alt' => 'This is image 2',
        ],
      ],
    ]);
    $node->addTranslation('fr', [
      'title' => 'Mon Node de Test',
      'field_media' => [$media->id(), $media_un->id()],
      'field_image' => [
        [
          'target_id' => $image1->id(),
          'alt' => 'Le premier',
        ],
        [
          'target_id' => $image2->id(),
          'alt' => 'La deuxieme',
        ],
      ],
    ]);
    $node->save();
  }

  /**
   * Tests file_download_link with multilingual stuff.
   */
  public function testFileDownloadLinkMultilingual() {
    $this->drupalGet('node/1');
    $this->assertSession()->pageTextContains('My Media|This is an English description (100 bytes)');
    $this->assertSession()->pageTextContains('My Untranslated Media|This is untranslated (100 bytes)');
    $this->assertSession()->pageTextMatchesCount(2, '/My Media\|This is an English description \(100 bytes\)/');
    $this->assertSession()->pageTextMatchesCount(2, '/My Untranslated Media\|This is untranslated \(100 bytes\)/');
    $this->assertSession()->responseContains('/my-english-text.txt');
    $this->assertSession()->responseNotContains('/ma-texte-francaise.txt');
    $this->assertSession()->pageTextContains('This is image 1 (125 bytes)');
    $this->assertSession()->pageTextContains('This is image 2 (140 bytes)');
    $this->drupalGet('fr/node/1');
    $this->assertSession()->pageTextMatchesCount(2, '/Mon Media\|Les mots françaises\. \(110 bytes\)/');
    $this->assertSession()->pageTextMatchesCount(2, '/My Untranslated Media\|This is untranslated \(100 bytes\)/');
    $this->assertSession()->responseContains('/my-english-text.txt');
    $this->assertSession()->responseContains('/ma-texte-francaise.txt');
    $this->assertSession()->pageTextContains('Le premier (125 bytes)');
    $this->assertSession()->pageTextContains('La deuxieme (140 bytes)');
  }

}
