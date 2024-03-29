<?php

namespace Drupal\bank_consult_advertiser\Plugin\Menu\ContextualLink;


use Drupal\Core\Menu\ContextualLinkDefault;
use Symfony\Component\HttpFoundation\Request;

class AdvertiserContextualLink extends ContextualLinkDefault {

  public function getTitle(Request $request = NULL) {
    return parent::getTitle($request); // TODO: Change the autogenerated stub
  }

  public function getEntityTargetBundles() {
    $target_bundles = [];
    $definitions = $this->getPluginDefinition();
    if (isset($definitions['appears_on_bundles'])) {
      return $target_bundles[] = $definitions['appears_on_bundles'];
    }
    return $target_bundles;


  }

}
