<?php

namespace Drupal\cashme_categories\Controller;

use Drupal\Component\Utility\Xss;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Datetime\DateFormatter;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Render\Renderer;
use Drupal\Core\Url;
use Drupal\cashme_categories\Entity\CategoryInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class CategoryController.
 *
 *  Returns responses for Category routes.
 */
class CategoryController extends ControllerBase implements ContainerInjectionInterface {


  /**
   * The date formatter.
   *
   * @var \Drupal\Core\Datetime\DateFormatter
   */
  protected $dateFormatter;

  /**
   * The renderer.
   *
   * @var \Drupal\Core\Render\Renderer
   */
  protected $renderer;

  /**
   * Constructs a new CategoryController.
   *
   * @param \Drupal\Core\Datetime\DateFormatter $date_formatter
   *   The date formatter.
   * @param \Drupal\Core\Render\Renderer $renderer
   *   The renderer.
   */
  public function __construct(DateFormatter $date_formatter, Renderer $renderer) {
    $this->dateFormatter = $date_formatter;
    $this->renderer = $renderer;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('date.formatter'),
      $container->get('renderer')
    );
  }

  /**
   * Displays a Category revision.
   *
   * @param int $category_revision
   *   The Category revision ID.
   *
   * @return array
   *   An array suitable for drupal_render().
   */
  public function revisionShow($category_revision) {
    $category = $this->entityTypeManager()->getStorage('category')
      ->loadRevision($category_revision);
    $view_builder = $this->entityTypeManager()->getViewBuilder('category');

    return $view_builder->view($category);
  }

  /**
   * Page title callback for a Category revision.
   *
   * @param int $category_revision
   *   The Category revision ID.
   *
   * @return string
   *   The page title.
   */
  public function revisionPageTitle($category_revision) {
    $category = $this->entityTypeManager()->getStorage('category')
      ->loadRevision($category_revision);
    return $this->t('Revision of %title from %date', [
      '%title' => $category->label(),
      '%date' => $this->dateFormatter->format($category->getRevisionCreationTime()),
    ]);
  }

  /**
   * Generates an overview table of older revisions of a Category.
   *
   * @param \Drupal\cashme_categories\Entity\CategoryInterface $category
   *   A Category object.
   *
   * @return array
   *   An array as expected by drupal_render().
   */
  public function revisionOverview(CategoryInterface $category) {
    $account = $this->currentUser();
    $category_storage = $this->entityTypeManager()->getStorage('category');

    $langcode = $category->language()->getId();
    $langname = $category->language()->getName();
    $languages = $category->getTranslationLanguages();
    $has_translations = (count($languages) > 1);
    $build['#title'] = $has_translations ? $this->t('@langname revisions for %title', ['@langname' => $langname, '%title' => $category->label()]) : $this->t('Revisions for %title', ['%title' => $category->label()]);

    $header = [$this->t('Revision'), $this->t('Operations')];
    $revert_permission = (($account->hasPermission("revert all category revisions") || $account->hasPermission('administer category entities')));
    $delete_permission = (($account->hasPermission("delete all category revisions") || $account->hasPermission('administer category entities')));

    $rows = [];

    $vids = $category_storage->revisionIds($category);

    $latest_revision = TRUE;

    foreach (array_reverse($vids) as $vid) {
      /** @var \Drupal\cashme_categories\CategoryInterface $revision */
      $revision = $category_storage->loadRevision($vid);
      // Only show revisions that are affected by the language that is being
      // displayed.
      if ($revision->hasTranslation($langcode) && $revision->getTranslation($langcode)->isRevisionTranslationAffected()) {
        $username = [
          '#theme' => 'username',
          '#account' => $revision->getRevisionUser(),
        ];

        // Use revision link to link to revisions that are not active.
        $date = $this->dateFormatter->format($revision->getRevisionCreationTime(), 'short');
        if ($vid != $category->getRevisionId()) {
          $link = $this->l($date, new Url('entity.category.revision', [
            'category' => $category->id(),
            'category_revision' => $vid,
          ]));
        }
        else {
          $link = $category->link($date);
        }

        $row = [];
        $column = [
          'data' => [
            '#type' => 'inline_template',
            '#template' => '{% trans %}{{ date }} by {{ username }}{% endtrans %}{% if message %}<p class="revision-log">{{ message }}</p>{% endif %}',
            '#context' => [
              'date' => $link,
              'username' => $this->renderer->renderPlain($username),
              'message' => [
                '#markup' => $revision->getRevisionLogMessage(),
                '#allowed_tags' => Xss::getHtmlTagList(),
              ],
            ],
          ],
        ];
        $row[] = $column;

        if ($latest_revision) {
          $row[] = [
            'data' => [
              '#prefix' => '<em>',
              '#markup' => $this->t('Current revision'),
              '#suffix' => '</em>',
            ],
          ];
          foreach ($row as &$current) {
            $current['class'] = ['revision-current'];
          }
          $latest_revision = FALSE;
        }
        else {
          $links = [];
          if ($revert_permission) {
            $links['revert'] = [
              'title' => $this->t('Revert'),
              'url' => $has_translations ?
              Url::fromRoute('entity.category.translation_revert', [
                'category' => $category->id(),
                'category_revision' => $vid,
                'langcode' => $langcode,
              ]) :
              Url::fromRoute('entity.category.revision_revert', [
                'category' => $category->id(),
                'category_revision' => $vid,
              ]),
            ];
          }

          if ($delete_permission) {
            $links['delete'] = [
              'title' => $this->t('Delete'),
              'url' => Url::fromRoute('entity.category.revision_delete', [
                'category' => $category->id(),
                'category_revision' => $vid,
              ]),
            ];
          }

          $row[] = [
            'data' => [
              '#type' => 'operations',
              '#links' => $links,
            ],
          ];
        }

        $rows[] = $row;
      }
    }

    $build['category_revisions_table'] = [
      '#theme' => 'table',
      '#rows' => $rows,
      '#header' => $header,
    ];

    return $build;
  }

}
