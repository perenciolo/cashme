<?php

namespace Drupal\cashme_spent;

use Drupal\Core\Entity\ContentEntityStorageInterface;
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
interface SpentStorageInterface extends ContentEntityStorageInterface {

  /**
   * Gets a list of Spent revision IDs for a specific Spent.
   *
   * @param \Drupal\cashme_spent\Entity\SpentInterface $entity
   *   The Spent entity.
   *
   * @return int[]
   *   Spent revision IDs (in ascending order).
   */
  public function revisionIds(SpentInterface $entity);

  /**
   * Gets a list of revision IDs having a given user as Spent author.
   *
   * @param \Drupal\Core\Session\AccountInterface $account
   *   The user entity.
   *
   * @return int[]
   *   Spent revision IDs (in ascending order).
   */
  public function userRevisionIds(AccountInterface $account);

  /**
   * Counts the number of revisions in the default language.
   *
   * @param \Drupal\cashme_spent\Entity\SpentInterface $entity
   *   The Spent entity.
   *
   * @return int
   *   The number of revisions in the default language.
   */
  public function countDefaultLanguageRevisions(SpentInterface $entity);

  /**
   * Unsets the language for all Spent with the given language.
   *
   * @param \Drupal\Core\Language\LanguageInterface $language
   *   The language object.
   */
  public function clearRevisionsLanguage(LanguageInterface $language);

}
