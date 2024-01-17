<?php

namespace Drupal\Tests\tome_sync\Functional;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Site\Settings;
use Drupal\Core\Url;
use Drupal\Tests\book\Functional\BookTestTrait;
use Drupal\Tests\BrowserTestBase;

/**
 * Tests that book support works.
 *
 * @group tome_sync
 */
class BookTest extends BrowserTestBase {

  use BookTestTrait;

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'book',
    'block',
    'tome_sync',
  ];

  /**
   * {@inheritdoc}
   */
  protected $defaultTheme = 'stark';

  /**
   * A user with permission to view a book and access printer-friendly version.
   *
   * @var object
   */
  protected $webUser;

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();
    $this->drupalPlaceBlock('system_breadcrumb_block');
    $this->drupalPlaceBlock('page_title_block');

    // Note: The format for ::writeSettings is really weird.
    $this->writeSettings([
      'settings' => [
        'tome_content_directory' => (object) [
          'value' => $this->siteDirectory . '/files/tome/content',
          'required' => TRUE,
        ],
        'tome_book_outline_directory' => (object) [
          'value' => $this->siteDirectory . '/files/tome/extra',
          'required' => TRUE,
        ],
      ],
    ]);
    $this->bookAuthor = $this->drupalCreateUser([
      'create new books',
      'create book content',
      'edit own book content',
      'add content to books',
    ]);
    $this->webUser = $this->drupalCreateUser([
      'access printer-friendly version',
    ]);
  }

  /**
   * @covers \Drupal\tome_sync\EventSubscriber\BookEventSubscriber::exportBookOutlines
   * @covers \Drupal\tome_sync\EventSubscriber\BookEventSubscriber::importBookOutlines
   */
  public function testBook() {
    // Create a book.
    $nodes = $this->createBook();
    $book = $this->book;

    // Assert that the exported JSON is not empty.
    $index_file = Settings::get('tome_book_outline_directory') . '/book_outlines.json';
    $this->assertFileExists($index_file);
    $outlines = json_decode(file_get_contents($index_file), TRUE);
    $this->assertNotEmpty($outlines);

    // Delete the book outline from the database.
    \Drupal::database()->truncate('book')->execute();

    // Trigger an import.
    \Drupal::service('tome_sync.book_event_subscriber')->importBookOutlines();

    // Test the book.
    $this->drupalLogin($this->webUser);
    $this->checkBookNode($book, [$nodes[0], $nodes[3], $nodes[4]], FALSE, FALSE, $nodes[0], []);
    $this->checkBookNode($nodes[0], [$nodes[1], $nodes[2]], $book, $book, $nodes[1], [$book]);
    $this->checkBookNode($nodes[1], NULL, $nodes[0], $nodes[0], $nodes[2], [$book, $nodes[0]]);
    $this->checkBookNode($nodes[2], NULL, $nodes[1], $nodes[0], $nodes[3], [$book, $nodes[0]]);
    $this->checkBookNode($nodes[3], NULL, $nodes[2], $book, $nodes[4], [$book]);
    $this->checkBookNode($nodes[4], NULL, $nodes[3], $book, FALSE, [$book]);
  }

  /**
   * Checks the outline of sub-pages; previous, up, and next.
   *
   * Also checks the printer friendly version of the outline. Overrides the
   * version from core.
   *
   * @todo remove this when https://drupal.org/node/3325730 is in.
   *
   * @param \Drupal\Core\Entity\EntityInterface $node
   *   Node to check.
   * @param $nodes
   *   Nodes that should be in outline.
   * @param $previous
   *   Previous link node.
   * @param $up
   *   Up link node.
   * @param $next
   *   Next link node.
   * @param array $breadcrumb
   *   The nodes that should be displayed in the breadcrumb.
   */
  public function checkBookNode(EntityInterface $node, $nodes, $previous, $up, $next, array $breadcrumb) {
    $this->drupalGet('node/' . $node->id());
    // Check outline structure.
    if ($nodes !== NULL) {
      $book_nav = $this->getSession()->getPage()->find('css', sprintf('nav[aria-labelledby="book-label-%s"] ul', $this->book->id()));
      $links = $book_nav->findAll('css', 'a');
      $this->assertCount(count($nodes), $links);
      foreach ($nodes as $delta => $node) {
        $link = $links[$delta];
        $this->assertEquals($node->label(), $link->getText());
        $this->assertEquals($node->toUrl()->toString(), $link->getAttribute('href'));
      }
    }

    // Check previous, up, and next links.
    if ($previous) {
      $previousEl = $this->assertSession()->elementExists('named_exact', ['link', 'Go to previous page']);
      $this->assertEquals($previous->toUrl()->toString(), $previousEl->getAttribute('href'));
    }

    if ($up) {
      $upEl = $this->assertSession()->elementExists('named_exact', ['link', 'Go to parent page']);
      $this->assertEquals($up->toUrl()->toString(), $upEl->getAttribute('href'));
    }

    if ($next) {
      $nextEl = $this->assertSession()->elementExists('named_exact', ['link', 'Go to next page']);
      $this->assertEquals($next->toUrl()->toString(), $nextEl->getAttribute('href'));
    }

    // Compute the expected breadcrumb.
    $expected_breadcrumb = [];
    $expected_breadcrumb[] = Url::fromRoute('<front>')->toString();
    foreach ($breadcrumb as $a_node) {
      $expected_breadcrumb[] = $a_node->toUrl()->toString();
    }

    // Fetch links in the current breadcrumb.
    $links = $this->xpath('//nav[@aria-labelledby="system-breadcrumb"]/ol/li/a');
    $got_breadcrumb = [];
    foreach ($links as $link) {
      $got_breadcrumb[] = $link->getAttribute('href');
    }

    // Compare expected and got breadcrumbs.
    $this->assertSame($expected_breadcrumb, $got_breadcrumb, 'The breadcrumb is correctly displayed on the page.');

    // Check printer friendly version.
    $this->drupalGet('book/export/html/' . $node->id());
    $this->assertSession()->pageTextContains($node->label());
    $this->assertSession()->responseContains($node->body->processed);
  }

}
