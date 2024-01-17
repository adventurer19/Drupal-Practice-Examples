<?php

namespace Drupal\rxp_form\Controller;

use Drupal\Component\Serialization\Json;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Url;
use Symfony\Component\HttpFoundation\Response;

/**
 * @todo .
 */
class FormController extends ControllerBase {

  /**
   * @todo .
   */
  public function modalForm() {
    // Return $this->generateFakeResponse();
    return $this->generateDummyLink();
    return [
      '#markup' => $this->t('hi'),
    ];
  }

  /**
   *
   */
  private function generateFakeResponse() {
    return new Response('This is a dummy content');
  }

  /**
   *
   */
  private function generateDummyLink() {
    $build['modal_link'] = [
      '#type' => 'link',
      '#title' => $this->t('Modal Link to External Source'),
      '#url' => Url::fromUri('https://befused.com/drupal/modal/',['query' => $this->getDestinationArray()]),
      '#options' => [
        'attributes' => [
          'class' => ['use-ajax'],
          'data-dialog-type' => 'modal',
          'data-dialog-options' => Json::encode(['width' => 700]),
        ],
      ],
      '#attached' => ['library' => ['core/drupal.dialog.ajax']],
    ];
    return $build;


//    $build['niki_link'] = [
//      '#title' => $this
//        ->t('Niki Link Demo'),
//      '#type' => 'link',
//      '#url' => Url::fromRoute('node.add', ['node_type' => 'article']),
//      '#suffix' => "<br>",
//    ];
//    $url = 'https://www.google.com/search?q=drupal&sca_esv=581765006&source=hp&ei=NT9RZdHwHpaXxc8PiOaQgAg&iflsig=AO6bgOgAAAAAZVFNRZ6JzqRGQWuZa0jF5WF459tNudmD&ved=0ahUKEwiR3_jVr7-CAxWWS_EDHQgzBIAQ4dUDCA8&oq=drupal&gs_lp=Egdnd3Mtd2l6IgZkcnVwYWxIAFAAWABwAHgAkAEAmAEAoAEAqgEAuAEMyAEA&sclient=gws-wiz';
//    $parsed_url = parse_url($url);
//    parse_str($parsed_url['query'], $query_params);
//    $build['google_link'] = [
//      '#title' => $this->t('Google Link'),
//      '#type' => 'link',
//      '#url' => Url::fromUri('//www.google.com/'),
//      '#options' => [
//        'query' => $query_params,
//        'absolute' => TRUE,
//        'https' => TRUE,
//      ],
//      '#suffix' => "<br>",
//
//    ];
//    $build['url_user'] = [
//      '#type' => 'link',
//      '#title' => 'USER URL',
//      '#url' => Url::fromUserInput('/demo'),
//      '#suffix' => "<br>",
//
//
//    ];
  }

}
