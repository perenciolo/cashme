<?php

namespace Drupal\cashme_incomes;

use Drupal\Core\Entity\ContentEntityStorageInterface;
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
interface IncomeStorageInterface extends ContentEntityStorageInterface {

  /**
   * Gets a list of Income revision IDs for a specific Income.
   *
   * @param \Drupal\cashme_incomes\Entity\IncomeInterface $entity
   *   The Income entity.
   *
   * @return int[]
   *   Income revision IDs (in ascending order).
   */
  public function revisionIds(IncomeInterface $entity);

  /**
   * Gets a list of revision IDs having a given user as Income author.
   *
   * @param \Drupal\Core\Session\AccountInterface $account
   *   The user entity.
   *
   * @return int[]
   *   Income revision IDs (in ascending order).
   */
  public function userRevisionIds(AccountInterface $account);

  /**
   * Counts the number of revisions in the default language.
   *
   * @param \Drupal\cashme_incomes\Entity\IncomeInterface $entity
   *   The Income entity.
   *
   * @return int
   *   The number of revisions in the default language.
   */
  public function countDefaultLanguageRevisions(IncomeInterface $entity);

  /**
   * Unsets the language for all Income with the given language.
   *
   * @param \Drupal\Core\Language\LanguageInterface $language
   *   The language object.
   */
  public function clearRevisionsLanguage(LanguageInterface $language);

}
