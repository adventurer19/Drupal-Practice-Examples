<?php

namespace Drupal\bank_consult_controller\Plugin\Filter;

use Drupal\filter\Plugin\FilterBase;

/**
 * Provides a filter .
 *
 *
 * @Filter(
 *   id = "bank_consult_filter",
 *   title = @Translation("Bank Consult filter"),
 *   description = @Translation("Description demo..."),
 *   type = Drupal\filter\Plugin\FilterInterface::TYPE_HTML_RESTRICTOR
 * )
 */
class BankConsultFilter extends FilterBase {

  public function process($text, $langcode) {
    // TODO: Implement process() method.
  }

}