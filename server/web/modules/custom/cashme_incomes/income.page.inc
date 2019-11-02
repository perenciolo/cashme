<?php

/**
 * @file
 * Contains income.page.inc.
 *
 * Page callback for Income entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Income templates.
 *
 * Default template: income.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_income(array &$variables) {
  // Fetch Income Entity Object.
  $income = $variables['elements']['#income'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
