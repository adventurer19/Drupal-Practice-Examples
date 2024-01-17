<?php

namespace Drupal\rxp_access_control\Access;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Routing\Access\AccessInterface;
use Drupal\Core\Session\AccountInterface;

/**
 * Checks if passed parameter matches the route configuration.
 */
class MinUsernameLengthAccessChecker implements AccessInterface {

  /**
   * Custom access control logic. Check minimum username length.
   *
   * @param \Drupal\Core\Session\AccountInterface $account
   *   The user trying to access the route.
   * @param int $min_username_length
   *   Minimum username length.
   *
   * @return \Drupal\Core\Access\AccessResultInterface
   *   The access result.
   */
  public function access(AccountInterface $account, int $min_username_length) : AccountInterface {
    // When performing access control checks, you should always work with the
    // supplied AccountInterface object and not Drupal::currentUser().
    $name = $account->getDisplayName();
    if (strlen($name) >= $min_username_length) {
      return AccessResult::allowed();
    }

    return AccessResult::forbidden('Your username is not long enough. Must be at least 5 characters.');
  }

}
