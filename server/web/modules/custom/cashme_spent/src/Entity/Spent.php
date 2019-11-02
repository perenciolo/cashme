<?php

namespace Drupal\cashme_spent\Entity;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\EditorialContentEntityBase;
use Drupal\Core\Entity\RevisionableInterface;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityPublishedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\user\UserInterface;

/**
 * Defines the Spent entity.
 *
 * @ingroup cashme_spent
 *
 * @ContentEntityType(
 *   id = "spent",
 *   label = @Translation("Spent"),
 *   handlers = {
 *     "storage" = "Drupal\cashme_spent\SpentStorage",
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\cashme_spent\SpentListBuilder",
 *     "views_data" = "Drupal\cashme_spent\Entity\SpentViewsData",
 *     "translation" = "Drupal\cashme_spent\SpentTranslationHandler",
 *
 *     "form" = {
 *       "default" = "Drupal\cashme_spent\Form\SpentForm",
 *       "add" = "Drupal\cashme_spent\Form\SpentForm",
 *       "edit" = "Drupal\cashme_spent\Form\SpentForm",
 *       "delete" = "Drupal\cashme_spent\Form\SpentDeleteForm",
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\cashme_spent\SpentHtmlRouteProvider",
 *     },
 *     "access" = "Drupal\cashme_spent\SpentAccessControlHandler",
 *   },
 *   base_table = "spent",
 *   data_table = "spent_field_data",
 *   revision_table = "spent_revision",
 *   revision_data_table = "spent_field_revision",
 *   translatable = TRUE,
 *   permission_granularity = "bundle",
 *   admin_permission = "administer spent entities",
 *   entity_keys = {
 *     "id" = "id",
 *     "revision" = "vid",
 *     "uuid" = "uuid",
 *     "uid" = "user_id",
 *     "langcode" = "langcode",
 *     "published" = "status",
 *   },
 *   links = {
 *     "canonical" = "/admin/structure/cashme/spent/{spent}",
 *     "add-form" = "/admin/structure/cashme/spent/add",
 *     "edit-form" = "/admin/structure/cashme/spent/{spent}/edit",
 *     "delete-form" = "/admin/structure/cashme/spent/{spent}/delete",
 *     "version-history" = "/admin/structure/cashme/spent/{spent}/revisions",
 *     "revision" = "/admin/structure/cashme/spent/{spent}/revisions/{spent_revision}/view",
 *     "revision_revert" = "/admin/structure/cashme/spent/{spent}/revisions/{spent_revision}/revert",
 *     "revision_delete" = "/admin/structure/cashme/spent/{spent}/revisions/{spent_revision}/delete",
 *     "translation_revert" = "/admin/structure/cashme/spent/{spent}/revisions/{spent_revision}/revert/{langcode}",
 *     "collection" = "/admin/structure/cashme/spent",
 *   },
 *   field_ui_base_route = "spent.settings"
 * )
 */
class Spent extends EditorialContentEntityBase implements SpentInterface {

  use EntityChangedTrait;
  use EntityPublishedTrait;

  /**
   * {@inheritdoc}
   */
  public static function preCreate(EntityStorageInterface $storage_controller, array &$values) {
    parent::preCreate($storage_controller, $values);
    $values += [
      'user_id' => \Drupal::currentUser()->id(),
    ];
  }

  /**
   * {@inheritdoc}
   */
  protected function urlRouteParameters($rel) {
    $uri_route_parameters = parent::urlRouteParameters($rel);

    if ($rel === 'revision_revert' && $this instanceof RevisionableInterface) {
      $uri_route_parameters[$this->getEntityTypeId() . '_revision'] = $this->getRevisionId();
    }
    elseif ($rel === 'revision_delete' && $this instanceof RevisionableInterface) {
      $uri_route_parameters[$this->getEntityTypeId() . '_revision'] = $this->getRevisionId();
    }

    return $uri_route_parameters;
  }

  /**
   * {@inheritdoc}
   */
  public function preSave(EntityStorageInterface $storage) {
    parent::preSave($storage);

    foreach (array_keys($this->getTranslationLanguages()) as $langcode) {
      $translation = $this->getTranslation($langcode);

      // If no owner has been set explicitly, make the anonymous user the owner.
      if (!$translation->getOwner()) {
        $translation->setOwnerId(0);
      }
    }

    // If no revision author has been set explicitly,
    // make the spent owner the revision author.
    if (!$this->getRevisionUser()) {
      $this->setRevisionUserId($this->getOwnerId());
    }
  }

  /**
   * {@inheritdoc}
   */
  public function getName() {
    return $this->get('name')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setName($name) {
    $this->set('name', $name);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getCreatedTime() {
    return $this->get('created')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setCreatedTime($timestamp) {
    $this->set('created', $timestamp);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getOwner() {
    return $this->get('user_id')->entity;
  }

  /**
   * {@inheritdoc}
   */
  public function getOwnerId() {
    return $this->get('user_id')->target_id;
  }

  /**
   * {@inheritdoc}
   */
  public function setOwnerId($uid) {
    $this->set('user_id', $uid);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function setOwner(UserInterface $account) {
    $this->set('user_id', $account->id());
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields = parent::baseFieldDefinitions($entity_type);

    // Add the published field.
    $fields += static::publishedBaseFieldDefinitions($entity_type);

    $fields['category_id'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Spent Type'))
      ->setDescription(t('The category ID of the Spent entity.'))
      ->setSetting('target_type', 'category')
      ->setSetting('handler', 'default')
      ->setRevisionable(TRUE)
      ->setTranslatable(TRUE)
      ->setDisplayOptions('view', [
        'label' => 'hidden',
        'type' => 'rendered_entity',
        'weight' => 0,
      ])
      ->setDisplayOptions('form', [
        'type' => 'entity_reference_autocomplete',
        'weight' => 1,
        'settings' => [
          'match_operator' => 'CONTAINS',
          'size' => '60',
          'autocomplete_type' => 'tags',
          'placeholder' => '',
        ],
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

      $fields['value'] = BaseFieldDefinition::create('decimal')
      ->setLabel(t('Value'))
      ->setDescription(t('The value of this spent.'))
      ->setRequired(TRUE)
      ->setTranslatable(TRUE)
      ->setDisplayOptions('view', array(
        'label' => 'hidden',
        'type' => 'number',
        'weight' => -5,
      ))
      ->setDisplayOptions('form', array(
        'type' => 'number',
        'weight' => 1,
      ))
      ->setDisplayConfigurable('form', TRUE);

      $fields['ref_date'] = BaseFieldDefinition::create('datetime')
      ->setLabel(t('The date of the spent'))
      ->setDescription(t('Indicates the date referent to the spent.'))
      ->setSetting('datetime_type', 'date')
      ->setRequired(true)
      ->setDisplayOptions('view', array(
        'label' => 'hidden',
        'type' => 'string',
        'weight' => -5,
      ))
      ->setDisplayOptions('form', [
        'weight' => 2,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['user_id'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Authored by'))
      ->setDescription(t('The user ID of author of the Spent entity.'))
      ->setRevisionable(TRUE)
      ->setSetting('target_type', 'user')
      ->setSetting('handler', 'default')
      ->setTranslatable(TRUE)
      ->setDisplayOptions('view', [
        'label' => 'hidden',
        'type' => 'author',
        'weight' => -5,
      ])
      ->setDisplayOptions('form', [
        'type' => 'entity_reference_autocomplete',
        'weight' => 5,
        'settings' => [
          'match_operator' => 'CONTAINS',
          'size' => '60',
          'autocomplete_type' => 'tags',
          'placeholder' => '',
        ],
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['status']->setDescription(t('A boolean indicating whether the Spent is published.'))
      ->setDisplayOptions('form', [
        'type' => 'boolean_checkbox',
        'weight' => 20,
      ]);

    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Created'))
      ->setDescription(t('The time that the entity was created.'));

    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Changed'))
      ->setDescription(t('The time that the entity was last edited.'));

    $fields['revision_translation_affected'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Revision translation affected'))
      ->setDescription(t('Indicates if the last edit of a translation belongs to current revision.'))
      ->setReadOnly(TRUE)
      ->setRevisionable(TRUE)
      ->setTranslatable(TRUE);

    return $fields;
  }

}
