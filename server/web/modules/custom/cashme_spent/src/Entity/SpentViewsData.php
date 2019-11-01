<?php

namespace Drupal\cashme_spent\Entity;

use Drupal\views\EntityViewsData;

/**
 * Provides Views data for Spent entities.
 */
class SpentViewsData extends EntityViewsData {

  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    $data = parent::getViewsData();

    // Additional information for Views integration, such as table joins, can be
    // put here.
    return $data;
  }

}
