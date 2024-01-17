<?php

namespace Drupal\rxp_render\Element;

use Drupal\Core\Render\Element\RenderElement;
use Drupal\Core\Url;

/**
 * Provides a custom render element.
 *
 * @RenderElement("my_element")
 */
class MyElement extends RenderElement {

  public function getInfo() {
    $class = get_class($this);
    return [
      '#theme' => 'my_element',
      '#label' => 'Default Label',
      '#description' => 'Default Description',
      '#link' => Url::fromUserInput('/node/1'),
      '#pre_render' => [
        [$class, 'preRenderMyElement'],
      ],
    ];
  }

  public static function preRenderMyElement($element) {
    $element['#random_number'] = rand(5,10);
    return $element;
    // ... (Add the code for pre-rendering the element)
  }

}