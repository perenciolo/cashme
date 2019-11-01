<?php

namespace Drupal\cash_categories\Entity;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\cash_categories\CategoryInterface;
use Drupal\user\UserInterface;
use Drupal\Core\Entity\EntityChangedTrait;

/**
 * Defines the CashCategories entity.
 *
 * @ingroup cash_categories
 *
 * [...]
 *
 * @ContentEntityType(
 *  id = "cash_categories_categories",
 *  label = @Translation("Category entity"),
 *  handlers = {
 *    "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *    "list_builder" = "Drupal\cash_categories\Entity\Controller\CategoriesListBuilder",
 *    "form" = {
 *      "add" = "Drupal\cash_categories\Form\CategoryForm",
 *      "edit" = "Drupal\cash_categories\Form\CategoryForm",
 *      "delete" = "Drupal\cash_categories\Form\CategoryDeleteForm",
 *    },
 *    "access" = "Drupal\cash_categories\CategoryAccessControlHandler",
 *  },
 *  list_cache_contexts = { "user" },
 *  base_table = "category",
 *  admin_permission = "administer category entity",
 *  entity_keys = {
 *    "id" = "id",
 *    "label" = "name",
 *    "uuid" = "uuid"
 *  },
 *  links = {
 *    "canonical" = "/cash_categories_categories/{cash_categories_categories}",
 *    "edit-form" = "/cash_categories_categories/{cash_categories_categories}/edit",
 *    "collection" = "/cash_categories_categories/list"
 *  },
 *  field_ui_base_route = "cash_categories.categories_settings",
 * )
 */
class Category extends ContentEntityBase implements CategoryInterface
{
  use EntityChangedTrait;
  /**
   * {@inheritdoc}
   * When a new entity instance is added, set the user_id entity reference to
   * the current user as the creator of the instance.
   */
  public static function preCreate(EntityStorageInterface $storage_controller, array &$values) {
    parent::preCreate($storage_controller, $values);

    $values += [
      "user_id" => \Drupal::currentUser()->id(),
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getOwner() {
    return $this->get("user_id")->entity;
  }

  /**
   * {@inheritdoc}
   */
  public function getOwnerId() {
    return $this->get("user_id")->target_id;
  }

  /**
   * {@inheritdoc}
   */
  public function setOwnerId($uid) {
    $this->set("user_id", $uid);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function setOwner(UserInterface $account) {
    $this->set("user_id", $account->id());
    return $this;
  }

  /**
   * {@inheritdoc}
   *
   * Define the field properties here.
   *
   * Field name, type and size determine the table structure.
   *
   * In addition, we can define how the field and its content can be manipulated
   * in the GUI. The behaviour of the widgets used can be determined here.
   */
  public static function BaseFieldDefinitions(EntityTypeInterface $entity_type) {
    // Standard field, used as unique if primary index.
    $fields["id"] = BaseFieldDefinition::create("integer")
    ->setLabel(t("ID"))
    ->setDescription(t("The ID of the Category entity."))
    ->setReadOnly(TRUE);

    // Standard field, unique outside of the scope of the current project.
    $fields["uuid"] = BaseFieldDefinition::create("uuid")
    ->setLabel(t("UUID"))
    ->setDescription(t("The UUID of the Category Entity."))
    ->setReadOnly(TRUE);

    // Category Name field.
    $fields["category_name"] = BaseFieldDefinition::create("string")
    ->setLabel(t("Category Name"))
    ->setDescription(t("The name of the Category entity."))
    ->setSettings([
      "default_value" => "",
      "max_length" => 255,
      "text_processing" => 0,
    ])
    ->setDisplayOptions("view", [
      "label" => "above",
      "type" => "string",
      "weight" => -5,
    ])
    ->setDisplayOptions("form", [
      "type" => "string_textfield",
      "weight" => -5,
    ])
    ->setDisplayConfigurable("form", TRUE)
    ->setDisplayConfigurable("view", TRUE);

    // Owner field of the Category.
    // Entity reference field, holds the reference to the user object.
    // The view shows the user name field of the user.
    // The form presents a auto complete field for the user name.
    $fields["user_id"] = BaseFieldDefinition::create("entity_reference")
    ->setLabel(t("User Name"))
    ->setDescription(t("The Name of the associated user."))
    ->setSetting("target_type", "user")
    ->setSetting("handler", "default")
    ->setDisplayOptions("view", [
      "label" => "above",
      "type" => "author",
      "weight" => -3,
    ])
    ->setDisplayOptions("form", [
      "type" => "entity_reference_autocomplete",
      "settings" => [
        "match_operator" => "CONTAINS",
        "size" => 60,
        "placeholder" => "",
      ],
      "weight" => -3,
    ])
    ->setDisplayConfigurable("form", TRUE)
    ->setDisplayConfigurable("view", TRUE);

    $fields["langcode"] = BaseFieldDefinition::create("language")
    ->setLabel(t("Language code"))
    ->setDescription(t("The language code of ContentEntityExample entity."));

    $fields["created"] = BaseFieldDefinition::create("created")
        ->setLabel(t("Created"))
        ->setDescription(t("The time that the entity was created."));

    $fields["changed"] = BaseFieldDefinition::create("changed")
        ->setLabel(t("Changed"))
        ->setDescription(t("The time that the entity was last edited."));

    return $fields;
  }
}

