<?php

namespace Drupal\rxp_install\Utils;

use Drupal\Component\Utility\Random;

/**
 * Util helper which generates page titles.
 */
class RXPPageTitleHandler {

  /**
   * Utility Random class.
   *
   * @var \Drupal\Component\Utility\Random
   */
  protected Random $random;

  /**
   * Generate title callback.
   */
  public function generateTitle(array $_title_arguments = [], $_title = ''): string {
    $len = rand(3, 15);
    return $_title.'@'.$this->random->word($len);
  }

  /**
   * Class constructor.
   */
  public function __construct() {
    $this->random = new Random();
  }

}
