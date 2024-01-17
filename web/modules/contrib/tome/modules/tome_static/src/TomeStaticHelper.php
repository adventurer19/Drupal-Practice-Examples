<?php

namespace Drupal\tome_static;

use Symfony\Component\HttpFoundation\Request;

@trigger_error(sprintf('%s is deprecated in tome:8.x-1.8 and is removed from tome:2.0.0. Use TomeStaticUrlHelper instead. See https://www.drupal.org/node/3323474', TomeStaticHelper::class), E_USER_DEPRECATED);

/**
 * Provides helpers for the Tome Static module.
 *
 * @deprecated in tome:8.x-1.8 and is removed from tome:2.0.0. Use
 *   TomeStaticUrlHelper instead
 *
 * @see https://www.drupal.org/node/3323474
 *
 * @internal
 */
trait TomeStaticHelper {

  /**
   * Sets the base URL for a given request.
   *
   * @param \Symfony\Component\HttpFoundation\Request $request
   *   The request.
   * @param string $base_url
   *   The base URL.
   *
   * @return array
   *   An array meant to be passed to ::restoreBaseUrl
   */
  public static function setBaseUrl(Request $request, $base_url) {
    return TomeStaticUrlHelper::setBaseUrl($request, $base_url);
  }

  /**
   * Restores the base URL for a request.
   *
   * @param \Symfony\Component\HttpFoundation\Request $request
   *   The request.
   * @param array $original_params
   *   The return value of ::setBaseUrl.
   */
  public static function restoreBaseUrl(Request $request, array $original_params) {
    TomeStaticUrlHelper::restoreBaseUrl($request, $original_params);
  }

}
