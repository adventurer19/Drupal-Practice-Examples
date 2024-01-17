<?php

namespace Drupal\rxp_render\Controller;

use Drupal\Component\EventDispatcher\ContainerAwareEventDispatcher;
use Drupal\Component\Utility\EmailValidator;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Render\RendererInterface;
use Drupal\node\Entity\Node;
use Drupal\rxp_render\Event\DemoEvent;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Response;

class DemoController extends ControllerBase {

  protected $emailValidator;

  protected RendererInterface $renderer;

  /**
   */
  protected ContainerAwareEventDispatcher $eventDispatcher;

  public function renderParticularTemplate() {
    /** @var \Twig\Environment $twig_service */

    $twig_service = \Drupal::service('twig');
    $template_path = \Drupal::moduleHandler()->getModule('rxp_render')
        ->getPath() . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'first.html.twig';
    $html_markup = $twig_service->load($template_path)
      ->render(['header' => 'Header Custom text']);
    return new Response($html_markup, 200);
  }

  public function main() {
    $element['first'] = [
      '#markup' => $this->t('Hello world'),
    ];
    $element['second'] = [
      '#type' => 'my_element',
      '#label' => $this->t('Example Label'),
      '#description' => $this->t(' Nikolay This is the description text.'),
    ];

    return $element;
  }

  public function index() {
    $build['examples_link'] = [
      '#title' => $this
        ->t('Render element link'),
      '#type' => 'link',
      '#ajax' => [
        'dialogType' => 'modal',
        'dialog' => ['height' => 400, 'width' => 700],
      ],

      //        'data-dialog-renderer' => "off_canvas"
      '#url' => \Drupal\Core\Url::fromRoute('node.add', ['node_type' => 'article']),
    ];
    return $build;
    return [
      '#markup' => '        <a class="edit-button use-ajax" 
            data-dialog-options="{&quot;width&quot;:400}" 
            data-dialog-renderer="off_canvas" 
            data-dialog-type="dialog" 
            href="/node/2">
            Third article displayed in a nice off canvas dialog.
        </a>
',
    ];

    return [
      '#markup' => '<a class="use-ajax" 
    data-dialog-options="{&quot;width&quot;:400}" 
    data-dialog-type="modal" 
    href="/node/1">
    First node displayed in modal dialog.
</a>',
    ];

    $node = Node::load(1);
    $module_handler = \Drupal::moduleHandler()
      ->invoke('mm_email', 'node_update', [$node]);
    $operation = 'update';
    $operation = 'delete';

    if ($operation === 'delete') {
      $event = new DemoEvent($node, $operation);
      $this->eventDispatcher->dispatch($event, DemoEvent::REMOVE_COURSE);
    }
    if ($operation === 'update') {
      $event = new DemoEvent($node, $operation);
      $this->eventDispatcher->dispatch($event, DemoEvent::UPDATE_NODE);
    }

    $data = [
      '#markup' => $this->t('This is random data'),
    ];
    return $data;
  }

  public function __construct(EmailValidator $emailValidator, ContainerAwareEventDispatcher $eventDispatcher, RendererInterface $renderer) {
    $this->emailValidator = $emailValidator;
    $this->eventDispatcher = $eventDispatcher;
    $this->renderer = $renderer;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('email.validator'),
      $container->get('event_dispatcher'),
      $container->get('renderer'),
    );
  }

}

