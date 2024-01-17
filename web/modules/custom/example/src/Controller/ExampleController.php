<?php

namespace Drupal\example\Controller;

use Drupal\Component\Serialization\Json;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Link;
use Drupal\Core\Url;
use Drupal\media\Entity\Media;
use Drupal\media\MediaInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class ExampleController extends ControllerBase {

  public function index(MediaInterface $video) {
//    $new_media = Media::create()
    return new JsonResponse($video->toArray());

  }

  public function main() {
    $link = Link::fromTextAndUrl('LinkTitle', Url::fromRoute('example.hello'));
//    return $link->toRenderable();
    return [
      '#type' => 'markup',
      '#markup' => $link>toString(),
    ];

  }
  public function hello() {
    return [
      '#markup' => $this->t('hello')
    ];
  }

}
