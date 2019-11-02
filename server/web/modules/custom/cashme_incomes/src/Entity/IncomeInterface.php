<?php

namespace Drupal\cashme_incomes\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\RevisionLogInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\Core\Entity\EntityPublishedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Income entities.
 *
 * @ingroup cashme_incomes
 */
interface IncomeInterface extends ContentEntityInterface, RevisionLogInterface, EntityChangedInterface, EntityPublishedInterface, EntityOwnerInterface {

  /**
   * Add get/set methods for your configuration properties here.
   */

  /**
   * Gets the Income name.
   *
   * @return string
   *   Name of the Income.
   */
  public function getName();

  /**
   * Sets the Income name.
   *
   * @param string $name
   *   The Income name.
   *
   * @return \Drupal\cashme_incomes\Entity\IncomeInterface
   *   The called Income entity.
   */
  public function setName($name);

  /**
   * Gets the Income creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Income.
   */
  public function getCreatedTime();

  /**
   * Sets the Income creation timestamp.
   *
   * @param int $timestamp
   *   The Income creation timestamp.
   *
   * @return \Drupal\cashme_incomes\Entity\IncomeInterface
   *   The called Income entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Gets the Income revision creation timestamp.
   *
   * @return int
   *   The UNIX timestamp of when this revision was created.
   */
  public function getRevisionCreationTime();

  /**
   * Sets the Income revision creation timestamp.
   *
   * @param int $timestamp
   *   The UNIX timestamp of when this revision was created.
   *
   * @return \Drupal\cashme_incomes\Entity\IncomeInterface
   *   The called Income entity.
   */
  public function setRevisionCreationTime($timestamp);

  /**
   * Gets the Income revision author.
   *
   * @return \Drupal\user\UserInterface
   *   The user entity for the revision author.
   */
  public function getRevisionUser();

  /**
   * Sets the Income revision author.
   *
   * @param int $uid
   *   The user ID of the revision author.
   *
   * @return \Drupal\cashme_incomes\Entity\IncomeInterface
   *   The called Income entity.
   */
  public function setRevisionUserId($uid);

}
