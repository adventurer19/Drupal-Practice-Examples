<?php

namespace Drupal\rxp_render\EventSubscriber;

use Drupal\rxp_render\Event\DemoEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class UserLoginSubscriber.
 *
 * @package Drupal\custom_events\EventSubscriber
 */
class DemoEventSubscriber implements EventSubscriberInterface {

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    return [
      DemoEvent::UPDATE_NODE => 'onNodeUpdate',
    ];
  }

  public function onNodeRemove(DemoEvent $event) {


  }

  public function onNodeUpdate(DemoEvent $event) {
   //
  }

}