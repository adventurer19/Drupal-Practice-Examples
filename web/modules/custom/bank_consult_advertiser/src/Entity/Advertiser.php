<?php

namespace Drupal\bank_consult_advertiser\Entity;

use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Entity\ContentEntityInterface;

/**
 * Defines the advertiser entity.
 *
 * @ingroup advertiser
 *
 * @ContentEntityType(
 *   id = "advertiser",
 *   label = @Translation("advertiser"),
 *   bundle_label = @Translation("My entity type type"),
 *   base_table = "advertiser",
 *   handlers = {
 *     "view_builder" = "Drupal\bank_consult_advertiser\Entity\Controller\AdvertiserViewBuilder",
 *     "access" = "Drupal\bank_consult_advertiser\AdvertiserAccessControlHandler",
 *     "route_provider" = {
 *        "html" = "Drupal\Core\Entity\Routing\AdminHtmlRouteProvider",
 *   },
 *     "list_builder" = "Drupal\bank_consult_advertiser\Entity\Controller\AdvertiserListBuilder",
 *     "form" = {
 *        "default" = "Drupal\bank_consult_advertiser\Form\AdvertiserForm",
 *        "add" = "Drupal\bank_consult_advertiser\Form\AdvertiserForm",
 *        "edit" = "Drupal\bank_consult_advertiser\Form\AdvertiserForm",
 *        "delete" = "Drupal\Core\Entity\ContentEntityDeleteForm",
 *     },
 *    },
 *   links = {
 *     "canonical" = "/advertiser/{advertiser}",
 *     "add-form" = "/advertiser/add/{advertiser_type}",
 *     "edit-form" = "/advertiser/{advertiser}/edit",
 *     "delete-form" = "/advertiser/{advertiser}/delete",
 *     "collection" = "/admin/advertisers"
 *    },
 *   entity_keys = {
 *     "id" = "id",
 *     "uuid" = "uuid",
 *     "label" = "title",
 *     "bundle" = "type",
 *   },
 *   admin_permission = "administer site configuration",
 *   bundle_entity_type = "advertiser_type",
 *   field_ui_base_route = "entity.advertiser_type.edit_form",
 * )
 */

class Advertiser extends ContentEntityBase implements ContentEntityInterface {

//  use EntityChangedTrait;

  public static function baseFieldDefinitions(EntityTypeInterface $entity_type): array {

    // Here we have id, uuid and the type/bundle as base field definitions.
    $fields = parent::baseFieldDefinitions($entity_type);

    $fields['status'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Status'))
      ->setDescription(t('Status of post action entity'))
      ->setDefaultValue(TRUE)
      ->setSettings(['on_label' => 'Published', 'off_label' => 'Unpublished'])
      ->setDisplayOptions('view', [
        'label' => 'visible',
        'type' => 'boolean',
        'weight' => 2,
      ])
      ->setDisplayOptions('form', [
        'type' => 'boolean_checkbox',
        'weight' => 2,
      ])
      ->setDisplayConfigurable('view', TRUE)
      ->setDisplayConfigurable('form', TRUE);

    $fields['title'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Title'))
      ->setRequired(TRUE)
      ->setTranslatable(TRUE)
      ->setSetting('max_length', 255)
      ->setDisplayOptions('view', [
        'label' => 'hidden',
        'type' => 'string',
        'weight' => -5,
      ])
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => -5,
      ])
      ->setDisplayConfigurable('form', TRUE);

    return $fields;
  }

}