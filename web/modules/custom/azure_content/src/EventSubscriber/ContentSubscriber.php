<?php

namespace Drupal\azure_content\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class ContentSubscriber implements EventSubscriberInterface {

  public function onViewRenderArray(ViewEvent $event) {
    $a = 1;
  }

  public static function getSubscribedEvents() {
    $events[KernelEvents::VIEW] = ['onViewRenderArray', 10000];
    return $events;
  }

}