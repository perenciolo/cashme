<?php

namespace Drupal\cashme_spent\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\RevisionLogInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\Core\Entity\EntityPublishedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Spent entities.
 *
 * @ingroup cashme_spent
 */
interface SpentInterface extends ContentEntityInterface, RevisionLogInterface, EntityChangedInterface, EntityPublishedInterface, EntityOwnerInterface {

  /**
   * Add get/set methods for your configuration properties here.
   */

  /**
   * Gets the Spent name.
   *
   * @return string
   *   Name of the Spent.
   */
  public function getName();

  /**
   * Sets the Spent name.
   *
   * @param string $name
   *   The Spent name.
   *
   * @return \Drupal\cashme_spent\Entity\SpentInterface
   *   The called Spent entity.
   */
  public function setName($name);

  /**
   * Gets the Spent creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Spent.
   */
  public function getCreatedTime();

  /**
   * Sets the Spent creation timestamp.
   *
   * @param int $timestamp
   *   The Spent creation timestamp.
   *
   * @return \Drupal\cashme_spent\Entity\SpentInterface
   *   The called Spent entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Gets the Spent revision creation timestamp.
   *
   * @return int
   *   The UNIX timestamp of when this revision was created.
   */
  public function getRevisionCreationTime();

  /**
   * Sets the Spent revision creation timestamp.
   *
   * @param int $timestamp
   *   The UNIX timestamp of when this revision was created.
   *
   * @return \Drupal\cashme_spent\Entity\SpentInterface
   *   The called Spent entity.
   */
  public function setRevisionCreationTime($timestamp);

  /**
   * Gets the Spent revision author.
   *
   * @return \Drupal\user\UserInterface
   *   The user entity for the revision author.
   */
  public function getRevisionUser();

  /**
   * Sets the Spent revision author.
   *
   * @param int $uid
   *   The user ID of the revision author.
   *
   * @return \Drupal\cashme_spent\Entity\SpentInterface
   *   The called Spent entity.
   */
  public function setRevisionUserId($uid);

}
