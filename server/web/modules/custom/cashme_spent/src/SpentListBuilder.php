<?php

namespace Drupal\cashme_spent;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Link;

/**
 * Defines a class to build a listing of Spent entities.
 *
 * @ingroup cashme_spent
 */
class SpentListBuilder extends EntityListBuilder {

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['id'] = $this->t('ID');
    $header['category_id'] = $this->t('Category');
    $header['value'] = $this->t('Value');
    $header['user_id'] = $this->t('Author');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /* @var \Drupal\cashme_spent\Entity\Spent $entity */
    $row['id'] = $entity->id();
    $row['category_id'] =  count($entity->get('category_id')->referencedEntities()) > 0 ? $entity->get('category_id')->referencedEntities()[0]->get('name')->value: '';
    $row['value'] = $entity->get('value')->value;
    $row['user_id'] =  count($entity->get('user_id')->referencedEntities()) > 0 ? $entity->get('user_id')->referencedEntities()[0]->get('name')->value: '';
    return $row + parent::buildRow($entity);
  }

}
