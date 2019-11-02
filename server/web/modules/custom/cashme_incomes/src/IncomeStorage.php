<?php

namespace Drupal\cashme_incomes;

use Drupal\Core\Entity\Sql\SqlContentEntityStorage;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Language\LanguageInterface;
use Drupal\cashme_incomes\Entity\IncomeInterface;

/**
 * Defines the storage handler class for Income entities.
 *
 * This extends the base storage class, adding required special handling for
 * Income entities.
 *
 * @ingroup cashme_incomes
 */
class IncomeStorage extends SqlContentEntityStorage implements IncomeStorageInterface {

  /**
   * {@inheritdoc}
   */
  public function revisionIds(IncomeInterface $entity) {
    return $this->database->query(
      'SELECT vid FROM {income_revision} WHERE id=:id ORDER BY vid',
      [':id' => $entity->id()]
    )->fetchCol();
  }

  /**
   * {@inheritdoc}
   */
  public function userRevisionIds(AccountInterface $account) {
    return $this->database->query(
      'SELECT vid FROM {income_field_revision} WHERE uid = :uid ORDER BY vid',
      [':uid' => $account->id()]
    )->fetchCol();
  }

  /**
   * {@inheritdoc}
   */
  public function countDefaultLanguageRevisions(IncomeInterface $entity) {
    return $this->database->query('SELECT COUNT(*) FROM {income_field_revision} WHERE id = :id AND default_langcode = 1', [':id' => $entity->id()])
      ->fetchField();
  }

  /**
   * {@inheritdoc}
   */
  public function clearRevisionsLanguage(LanguageInterface $language) {
    return $this->database->update('income_revision')
      ->fields(['langcode' => LanguageInterface::LANGCODE_NOT_SPECIFIED])
      ->condition('langcode', $language->getId())
      ->execute();
  }

}
