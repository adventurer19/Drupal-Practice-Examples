<?php

namespace Drupal\bank_consult_queue\demo;

use Drupal\Core\DependencyInjection\ContainerInjectionInterface;

/**
 * Phone class demo purpose.
 */
class Phone implements ContainerInjectionInterface {

  /**
   * @var array
   */
  protected array $data;

  /**
   * Creates a Phone class which is used for demo purpose.
   *
   * @param array $data
   */
  public function __construct(array $data) {
    $this->data = $data;
  }

  /**
   * @inheritDoc
   */
  public static function create($data) {
    if (!is_array($data)) {
      $data = [];
    }
    return new static($data);
  }

}