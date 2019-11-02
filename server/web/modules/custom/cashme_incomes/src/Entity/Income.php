<?php

namespace Drupal\cashme_incomes\Entity;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\EditorialContentEntityBase;
use Drupal\Core\Entity\RevisionableInterface;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityPublishedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\user\UserInterface;

/**
 * Defines the Income entity.
 *
 * @ingroup cashme_incomes
 *
 * @ContentEntityType(
 *   id = "income",
 *   label = @Translation("Income"),
 *   handlers = {
 *     "storage" = "Drupal\cashme_incomes\IncomeStorage",
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\cashme_incomes\IncomeListBuilder",
 *     "views_data" = "Drupal\cashme_incomes\Entity\IncomeViewsData",
 *     "translation" = "Drupal\cashme_incomes\IncomeTranslationHandler",
 *
 *     "form" = {
 *       "default" = "Drupal\cashme_incomes\Form\IncomeForm",
 *       "add" = "Drupal\cashme_incomes\Form\IncomeForm",
 *       "edit" = "Drupal\cashme_incomes\Form\IncomeForm",
 *       "delete" = "Drupal\cashme_incomes\Form\IncomeDeleteForm",
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\cashme_incomes\IncomeHtmlRouteProvider",
 *     },
 *     "access" = "Drupal\cashme_incomes\IncomeAccessControlHandler",
 *   },
 *   base_table = "income",
 *   data_table = "income_field_data",
 *   revision_table = "income_revision",
 *   revision_data_table = "income_field_revision",
 *   translatable = TRUE,
 *   permission_granularity = "bundle",
 *   admin_permission = "administer income entities",
 *   entity_keys = {
 *     "id" = "id",
 *     "revision" = "vid",
 *     "uuid" = "uuid",
 *     "uid" = "user_id",
 *     "langcode" = "langcode",
 *     "published" = "status",
 *   },
 *   links = {
 *     "canonical" = "/admin/structure/cashme/income/{income}",
 *     "add-form" = "/admin/structure/cashme/income/add",
 *     "edit-form" = "/admin/structure/cashme/income/{income}/edit",
 *     "delete-form" = "/admin/structure/cashme/income/{income}/delete",
 *     "version-history" = "/admin/structure/cashme/income/{income}/revisions",
 *     "revision" = "/admin/structure/cashme/income/{income}/revisions/{income_revision}/view",
 *     "revision_revert" = "/admin/structure/cashme/income/{income}/revisions/{income_revision}/revert",
 *     "revision_delete" = "/admin/structure/cashme/income/{income}/revisions/{income_revision}/delete",
 *     "translation_revert" = "/admin/structure/cashme/income/{income}/revisions/{income_revision}/revert/{langcode}",
 *     "collection" = "/admin/structure/cashme/income",
 *   },
 *   field_ui_base_route = "income.settings"
 * )
 */
class Income extends EditorialContentEntityBase implements IncomeInterface {

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
    // make the income owner the revision author.
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
      ->setLabel(t('Income Type'))
      ->setDescription(t('The category ID of the Income entity.'))
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
      ->setDescription(t('The value of this income.'))
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
      ->setLabel(t('The date of the income'))
      ->setDescription(t('Indicates the date referent to the income.'))
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
      ->setDescription(t('The user ID of author of the Income entity.'))
      ->setRevisionable(TRUE)
      ->setSetting('target_type', 'user')
      ->setSetting('handler', 'default')
      ->setTranslatable(TRUE)
      ->setDisplayOptions('view', [
        'label' => 'hidden',
        'type' => 'author',
        'weight' => 0,
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

    $fields['status']->setDescription(t('A boolean indicating whether the Income is published.'))
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
