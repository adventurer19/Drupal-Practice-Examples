<?php

namespace Drupal\bank_consult_advertiser\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBundleBase;

/**
 * Advertiser Type
 *
 * @ConfigEntityType(
 *   id = "advertiser_type",
 *   label = @Translation("Advertiser Type"),
 *   bundle_of = "advertiser",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid",
 *     "description" = "description",
 *   },
 *   config_prefix = "advertiser_type",
 *   config_export = {
 *     "id",
 *     "label",
 *     "description",
 *   },
 *   handlers = {
 *     "list_builder" = "Drupal\bank_consult_advertiser\Entity\Controller\AdvertiserTypeListBuilder",
 *     "form" = {
 *       "default" = "Drupal\bank_consult_advertiser\Form\AdvertiserTypeEntityForm",
 *       "add" = "Drupal\bank_consult_advertiser\Form\AdvertiserTypeEntityForm",
 *       "edit" = "Drupal\bank_consult_advertiser\Form\AdvertiserTypeEntityForm",
 *       "delete" = "Drupal\Core\Entity\EntityDeleteForm",
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\Core\Entity\Routing\AdminHtmlRouteProvider",
 *     },
 *   },
 *   admin_permission = "administer site configuration",
 *   links = {
 *     "canonical" = "/admin/structure/advertiser_type/{advertiser_type}",
 *     "add-form" = "/admin/structure/advertiser_type/add",
 *     "edit-form" = "/admin/structure/advertiser_type/{advertiser_type}/edit",
 *     "delete-form" = "/admin/structure/advertiser_type/{advertiser_type}/delete",
 *     "collection" = "/admin/advertiser/add",
 *   }
 * )
 */
class AdvertiserType extends ConfigEntityBundleBase {

  public function getDescription() {
    $value = '';
    $entity_definition = $this->getEntityType();
    if ($field_name = $entity_definition->getKey('description')) {
      $value = $this->get($field_name);
    }
    return $value;
  }

}
