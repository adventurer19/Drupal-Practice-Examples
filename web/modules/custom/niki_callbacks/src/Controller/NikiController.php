<?php

namespace Drupal\niki_callbacks\Controller;

use Drupal\block\Entity\Block;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Security\DoTrustedCallbackTrait;
use Drupal\Core\Security\TrustedCallbackInterface;

class NikiController extends ControllerBase implements TrustedCallbackInterface {

  use DoTrustedCallbackTrait;

  public function getItem() {
    return 5;
  }

  public function main(): array {
    $build = [];
    // This is a demo only to render a block
    $block_view_builder = \Drupal::entityTypeManager()->getViewBuilder('block');
    $blocks = Block::loadMultiple();
    $rendered_blocks = $block_view_builder->viewMultiple($blocks);
    $build['blocks'] = $rendered_blocks;

    $data = $this->doTrustedCallback('Drupal\niki_callbacks\Controller\NikiController::getItem', [], 'GG');
    $build['content'] = [
      '#markup' => $this->t('This is trusted callback module.' . $data),
    ];
    $build['no-lazy'] = [
      '#markup' => date('r', time()),
    ];
    $build['lazy'] = [
      '#lazy_builder' => [$this::class . '::my_module_lazy_builder_function',['r']],
      '#create_placeholder' => TRUE,
    ];
    return $build;
  }

  // Callback function for #lazy_builder.
  public static function my_module_lazy_builder_function($format) {
    // Perform complex operations and return the final render array.
    return [
      '#markup' => date($format, time()) . rand(100,999),
      '#cache' => [
        'max-age' => 0,
      ],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public static function trustedCallbacks() {
    return ['getItem', 'getItemTwo', 'my_module_lazy_builder_function'];
  }

}