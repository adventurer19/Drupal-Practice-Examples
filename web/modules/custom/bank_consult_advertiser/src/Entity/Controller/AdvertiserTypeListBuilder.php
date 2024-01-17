<?php

namespace Drupal\bank_consult_advertiser\Entity\Controller;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Routing\UrlGeneratorInterface;
use Drupal\Core\Url;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a list controller for advertiser entity.
 *
 * @ingroup advertiser
 */
class AdvertiserTypeListBuilder extends EntityListBuilder {

  /**
   * The url generator.
   *
   * @var \Drupal\Core\Routing\UrlGeneratorInterface
   */
  protected $urlGenerator;

  /**
   * {@inheritdoc}
   */
  public static function createInstance(ContainerInterface $container, EntityTypeInterface $entity_type) {
    return new static(
      $entity_type,
      $container->get('entity_type.manager')->getStorage($entity_type->id()),
      $container->get('url_generator')
    );
  }

  /**
   * Constructs a new ContactListBuilder object.
   *
   * @param \Drupal\Core\Entity\EntityTypeInterface $entity_type
   *   The entity type definition.
   * @param \Drupal\Core\Entity\EntityStorageInterface $storage
   *   The entity storage class.
   * @param \Drupal\Core\Routing\UrlGeneratorInterface $url_generator
   *   The url generator.
   */
  public function __construct(EntityTypeInterface $entity_type, EntityStorageInterface $storage, UrlGeneratorInterface $url_generator) {
    parent::__construct($entity_type, $storage);
    $this->urlGenerator = $url_generator;
  }

  /**
   * {@inheritdoc}
   *
   * We override ::render() so that we can add our own content above the table.
   * parent::render() is where EntityListBuilder creates the table using our
   * buildHeader() and buildRow() implementations.
   */
  public function render() {
    $build['description'] = [
      '#markup' => $this->t('List of available advertise types to create.'),
    ];
    $build['table'] = parent::render();
    $build['links'] = [
      '#title' => $this->t('My link'),
      '#type' => 'link',
      '#ajax' => [
        'dialogType' => 'modal',
        'dialog' => ['height' => 400, 'width' => 700],
      ],
      '#url' => Url::fromRoute('node.add', ['node_type' => 'article']),
    ];
    return $build;
  }

  /**
   * {@inheritdoc}
   *
   * Building the header and content lines for the contact list.
   *
   * Calling the parent::buildHeader() adds a column for the possible actions
   * and inserts the 'edit' and 'delete' links as defined for the entity type.
   */
  public function buildHeader() {
    $header['label'] = $this->t('Advertiser Type');
    $header['description'] = $this->t('Advertiser Type Description');
    $header['links'] = $this->t('Links');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity): array {
    $url = Url::fromRoute('entity.advertiser.add_form',['advertiser_type' => $entity->id()]);
    $entity_label = $entity->label();
    $row['label']['data'] = [
      '#type' => 'item',
      '#description_display' => 'after',
      '#markup' => $this->t("<a href=\":config\">$entity_label</a>", [
        ':config' => $url->toString(),
      ]),
      '#description' => $this->t('This link will open %advertiser_type type add form',['%advertiser_type' => $entity->label()]),
    ];
    $row['description'] = $this->t($entity->getDescription() ?? '');
    $row['links']['data'] = [
      '#title' => $this->t('Ajax add link'),
      '#type' => 'link',
      '#ajax' => [
        'dialogType' => 'modal',
        'dialog' => ['height' => 400, 'width' => 700],
      ],
      '#url' => $url,
    ];


    return $row + parent::buildRow($entity);
  }

}

