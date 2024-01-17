<?php

namespace Drupal\rxp_access_control\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\node\Entity\Node;
use Drupal\node\NodeInterface;

/**
 * Access Control Controller.
 */
class RXPAccessControlController extends ControllerBase {

  /**
   * Renders the node from route params.
   *
   * @param \Drupal\node\NodeInterface $node
   *
   *   Node entity.
   *
   * @return array
   *   Render array of built node view.
   */
  public function index(NodeInterface $node): array {
    // $render_controller = \Drupal::entityTypeManager()->getViewBuilder($entity->getEntityTypeId());
    //    $render_output = $render_controller->view($entity, $view_mode, $langcode);
    $entity_type_manager = $this->entityTypeManager();
    $entity_render = $entity_type_manager->getViewBuilder('node');
    return $entity_render->view($node);
  }

}
