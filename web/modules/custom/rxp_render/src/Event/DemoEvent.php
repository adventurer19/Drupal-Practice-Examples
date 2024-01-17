<?php

namespace Drupal\rxp_render\Event;

use Drupal\Component\EventDispatcher\Event;
use Drupal\node\NodeInterface;

class DemoEvent extends Event {

  const UPDATE_NODE = 'node.update';

  const REMOVE_COURSE = 'node.delete';

  protected string $key;

  protected NodeInterface $node;

  public function __construct(NodeInterface $node, string $key) {
    $this->node = $node;
    $this->key = $key;
  }

  public function getKey(): string {
    return $this->key;
  }

  public function getNode(): NodeInterface {
    return $this->node;
  }


}