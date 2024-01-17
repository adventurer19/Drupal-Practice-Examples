<?php

namespace Drupal\bank_consult_jsonapi\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\node\Entity\Node;
use Symfony\Component\HttpFoundation\JsonResponse;

class BankJsonController extends ControllerBase {

  public function content() {
    $nodes = Node::loadMultiple();
    $node_data = [];

    foreach ($nodes as $node) {
      $node_data[] = [
        'id' => $node->id(),
        'title' => $node->getTitle(),
        'body' => $node->get('body')->value,
        // Add more fields as needed.
      ];
    }
    return new JsonResponse([ 'data' => $node_data, 'method' => 'GET', 'status'=> 200]);
  }

}