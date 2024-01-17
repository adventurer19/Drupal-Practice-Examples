<?php

namespace Drupal\rxp_render\Controller;

use Drupal\Component\Serialization\Json;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\OpenModalDialogCommand;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Render\RendererInterface;
use Drupal\Core\Url;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 *
 */
class RenderController extends ControllerBase {

  protected RendererInterface $renderer;

  /**
   * Render custom twig template.
   */
  public function renderMyCustomTwig(): array {
    return [
      '#theme' => 'my_first_theme_hook',
      '#title' => 'Niki Pench Template',
    ];
  }

  /**
   *
   */
  public function modalContent(): array {
    // Generate and return the content to be displayed in the modal.
    return [
      '#markup' => 'This is the content for the modal dialog.',
    ];
  }

  /**
   * Modal Test Demo callback.
   */
  public function modalTestDemo(): AjaxResponse {
    $modal_url = Url::fromRoute('rxp_render.modal_test');
    $modalOptions = [
      'width' => '80%',
      'height' => '80%',
      'dialogClass' => 'custom-modal-class',
      'title' => 'My Custom Modal',
    ];
    // Create an Ajax response to open the modal dialog.
    $response = new AjaxResponse();
    $response->addCommand(new OpenModalDialogCommand('My Custom Modal', $modal_url, $modalOptions));

    // Return the response to trigger the modal.
    return $response;
  }

  /**
   *
   */
  public function index() {
    $build = [];
    $build['node_add_dialog'] = [
      '#type' => 'link',
      '#title' => $this->t('Some text'),
      // $this->getDestinationArray() is used to create a ?destination= style query
      // string so that after a form is submitted in the modal you return to the
      // current page.
      '#url' => Url::fromRoute('node.add', ['node_type' => 'article'], ['query' => $this->getDestinationArray()]),
      '#options' => [
        'attributes' => [
          // Adding the class 'use-ajax' tells the Drupal AJAX system to process
          // this link, and bind an event handler so that when someone clicks on the
          // link we make an AJAX request instead of just linking to the URL
          // directly.
          'class' => ['use-ajax'],
          // This data attribute tells Drupal to use the ModalRenderer
          // (application/vnd.drupal-modal) to handle this particular request rather
          // then the normal MainContentRenderer.
          'data-dialog-type' => 'modal',
          // This contains settings to pass to the Drupal modal dialog JavaScript,
          // in this case setting the width of the modal window that'll be opened.
          'data-dialog-options' => Json::encode(['width' => 700]),
        ],
      ],
      // In order for the above classes and data attributes to do anything we also
      // need to attach the relevant JavaScript.
      '#attached' => ['library' => ['core/drupal.dialog.ajax']],
    ];
    return $build;

    // Return [
    //      '#markup' => $this->t('hi'),
    //    ];.
  }

  /**
   *
   */
  public static function create(ContainerInterface $container) {
    $instance = parent::create($container);
    $instance->renderer = \Drupal::service('renderer');
    return $instance;
  }

}
