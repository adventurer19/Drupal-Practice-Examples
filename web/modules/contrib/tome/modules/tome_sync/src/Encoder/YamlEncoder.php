<?php

namespace Drupal\tome_sync\Encoder;

use Symfony\Component\Serializer\Encoder\YamlEncoder as BaseYamlEncoder;

/**
 * Adds YAML support for serialization.
 *
 * @internal
 */
class YamlEncoder extends BaseYamlEncoder {

  /**
   * The formats that this Encoder supports.
   *
   * @var array
   */
  protected static $format = ['tome_sync_yaml'];

  /**
   * {@inheritdoc}
   */
  public function supportsEncoding($format): bool {
    return in_array($format, static::$format);
  }

  /**
   * {@inheritdoc}
   */
  public function supportsDecoding($format): bool {
    return in_array($format, static::$format);
  }

}
