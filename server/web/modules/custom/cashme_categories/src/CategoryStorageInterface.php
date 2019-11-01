<?php

namespace Drupal\cashme_categories;

use Drupal\Core\Entity\ContentEntityStorageInterface;
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
interface CategoryStorageInterface extends ContentEntityStorageInterface {

  /**
   * Gets a list of Category revision IDs for a specific Category.
   *
   * @param \Drupal\cashme_categories\Entity\CategoryInterface $entity
   *   The Category entity.
   *
   * @return int[]
   *   Category revision IDs (in ascending order).
   */
  public function revisionIds(CategoryInterface $entity);

  /**
   * Gets a list of revision IDs having a given user as Category author.
   *
   * @param \Drupal\Core\Session\AccountInterface $account
   *   The user entity.
   *
   * @return int[]
   *   Category revision IDs (in ascending order).
   */
  public function userRevisionIds(AccountInterface $account);

  /**
   * Counts the number of revisions in the default language.
   *
   * @param \Drupal\cashme_categories\Entity\CategoryInterface $entity
   *   The Category entity.
   *
   * @return int
   *   The number of revisions in the default language.
   */
  public function countDefaultLanguageRevisions(CategoryInterface $entity);

  /**
   * Unsets the language for all Category with the given language.
   *
   * @param \Drupal\Core\Language\LanguageInterface $language
   *   The language object.
   */
  public function clearRevisionsLanguage(LanguageInterface $language);

}
