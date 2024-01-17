<?php

namespace Drupal\rxp_email\Utils;

use Drupal\Core\Mail\MailManagerInterface;

/**
 * EmailController helper.
 */
class EmailController {

  const MODULE_NAME = 'rxp_email';

  protected MailManagerInterface $mail_manager;

  /**
   *
   */
  public function sendEmails(string $key, object $entity) {
    $this->mail_manager->mail(self::MODULE_NAME, 'id_unique', '', 'en', ['data' => $entity]);
  }

  /**
   *
   */
  public function __construct(MailManagerInterface $mailManager) {
    $this->mail_manager = $mailManager;
  }

}
