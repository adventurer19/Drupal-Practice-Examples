<?php

namespace Drupal\rxp_jsonapi\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Routing\RouteMatch;
use Drupal\Core\Routing\UrlGenerator;
use Drupal\Core\Url;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class  JsonApiController extends ControllerBase {

  public function processData(Request $request) {
    $a =1;

    exit;
  }

  public function index() {
    $links = [];
    $entity_type_definitions = $this->entityTypeManager()->getDefinitions();
    foreach ($entity_type_definitions as $entity_type_id => $entity_type_definition) {
      $url = Url::fromRoute('rxp_jsonapi.' . $entity_type_id . '_collection',['param1' => 'value1'],[
        ['query' => ['param1' => 'pench'],
          'absolute' => TRUE
        ]]);

      // Get the string representation of the absolute URL.
      $json_api_links = [
        'href' => $url->toString(),
      ];

      $links[$entity_type_id] = $json_api_links;
    }
    $response = [
      'jsonapi' => [
        'version' => '1.0',
      ],
      'meta' => [
        'count' => count($links),
      ],
      'links' => $links,

    ];

    return new JsonResponse($response);
  }

}