<?php

namespace Drupal\cashme_categories;

use Drupal\Core\Entity\Sql\SqlContentEntityStorage;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Language\LanguageInterface;
use Drupal\cashme_categories\Entity\CategoryInterface;

/**
 * Defines the storage handler class for Category entities.
 *
 * This extends the base storage class, adding required special handling for
 * Category entities.
 *
 * @ingroup cashme_categories
 */
class CategoryStorage extends SqlContentEntityStorage implements CategoryStorageInterface {

  /**
   * {@inheritdoc}
   */
  public function revisionIds(CategoryInterface $entity) {
    return $this->database->query(
      'SELECT vid FROM {category_revision} WHERE id=:id ORDER BY vid',
      [':id' => $entity->id()]
    )->fetchCol();
  }

  /**
   * {@inheritdoc}
   */
  public function userRevisionIds(AccountInterface $account) {
    return $this->database->query(
      'SELECT vid FROM {category_field_revision} WHERE uid = :uid ORDER BY vid',
      [':uid' => $account->id()]
    )->fetchCol();
  }

  /**
   * {@inheritdoc}
   */
  public function countDefaultLanguageRevisions(CategoryInterface $entity) {
    return $this->database->query('SELECT COUNT(*) FROM {category_field_revision} WHERE id = :id AND default_langcode = 1', [':id' => $entity->id()])
      ->fetchField();
  }

  /**
   * {@inheritdoc}
   */
  public function clearRevisionsLanguage(LanguageInterface $language) {
    return $this->database->update('category_revision')
      ->fields(['langcode' => LanguageInterface::LANGCODE_NOT_SPECIFIED])
      ->condition('langcode', $language->getId())
      ->execute();
  }

}
