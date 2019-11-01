<?php

namespace Drupal\cashme_spent;

use Drupal\Core\Entity\Sql\SqlContentEntityStorage;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Language\LanguageInterface;
use Drupal\cashme_spent\Entity\SpentInterface;

/**
 * Defines the storage handler class for Spent entities.
 *
 * This extends the base storage class, adding required special handling for
 * Spent entities.
 *
 * @ingroup cashme_spent
 */
class SpentStorage extends SqlContentEntityStorage implements SpentStorageInterface {

  /**
   * {@inheritdoc}
   */
  public function revisionIds(SpentInterface $entity) {
    return $this->database->query(
      'SELECT vid FROM {spent_revision} WHERE id=:id ORDER BY vid',
      [':id' => $entity->id()]
    )->fetchCol();
  }

  /**
   * {@inheritdoc}
   */
  public function userRevisionIds(AccountInterface $account) {
    return $this->database->query(
      'SELECT vid FROM {spent_field_revision} WHERE uid = :uid ORDER BY vid',
      [':uid' => $account->id()]
    )->fetchCol();
  }

  /**
   * {@inheritdoc}
   */
  public function countDefaultLanguageRevisions(SpentInterface $entity) {
    return $this->database->query('SELECT COUNT(*) FROM {spent_field_revision} WHERE id = :id AND default_langcode = 1', [':id' => $entity->id()])
      ->fetchField();
  }

  /**
   * {@inheritdoc}
   */
  public function clearRevisionsLanguage(LanguageInterface $language) {
    return $this->database->update('spent_revision')
      ->fields(['langcode' => LanguageInterface::LANGCODE_NOT_SPECIFIED])
      ->condition('langcode', $language->getId())
      ->execute();
  }

}
