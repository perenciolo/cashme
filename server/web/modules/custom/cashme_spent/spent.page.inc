<?php

/**
 * @file
 * Contains spent.page.inc.
 *
 * Page callback for Spent entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Spent templates.
 *
 * Default template: spent.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_spent(array &$variables) {
  // Fetch Spent Entity Object.
  $spent = $variables['elements']['#spent'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
