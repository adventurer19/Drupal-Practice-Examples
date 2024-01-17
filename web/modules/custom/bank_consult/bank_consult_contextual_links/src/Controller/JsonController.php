<?php

namespace Drupal\bank_consult_contextual_links\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\node\Entity\Node;
use Drupal\node\NodeInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class JsonController extends ControllerBase {

  public function content(NodeInterface $node): JsonResponse {
    $data = [
      'data' => $node->toArray(),
    ];
    return new JsonResponse($data);
  }

  public function index() {
    $node = Node::load(2);
    $build = [];
    $build['node_contextual_link'] = [
      '#theme' => 'bank_consult_contextual',
      '#title' => 'Contextual links here',
      '#contextual_links' => [
        'node' => [
          'route_parameters' => ['node' => $node->id()],
        ],
      ],
    ];
    $build['text'] = [
      '#markup' => $this->t('Hi'),
    ];
    return $build;
  }

}