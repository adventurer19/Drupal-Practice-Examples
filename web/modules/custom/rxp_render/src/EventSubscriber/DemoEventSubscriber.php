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
      DemoEvent::REMOVE_COURSE => 'onNodeRemove',
    ];
  }

  public function onNodeRemove(DemoEvent $event) {


  }

  public function onNodeUpdate(DemoEvent $event) {
    $node = $event->getNode();
    $key = $event->getKey();
    $config = \Drupal::config('custom_emails_config.settings');
    $emails = array_merge([
      \Drupal::currentUser()
        ->getEmail(),
    ], $config->get('admin_emails'));
    foreach ($emails as $email) {
      $mailManager = \Drupal::service('plugin.manager.mail');
      $module = 'mm_email';
      $to = $email;
      $params['message'] = 'data';
      $params['node'] = $node;
      $params['node_title'] = 'data';
      $langcode = \Drupal::currentUser()->getPreferredLangcode();
      $send = TRUE;
      $result = $mailManager->mail($module, $key, $to, $langcode, $params, NULL, $send);
    }
  }

}