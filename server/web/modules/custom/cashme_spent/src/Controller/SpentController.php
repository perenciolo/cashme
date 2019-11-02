<?php

namespace Drupal\cashme_spent\Controller;

use Drupal\Component\Utility\Xss;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Datetime\DateFormatter;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Render\Renderer;
use Drupal\Core\Url;
use Drupal\cashme_spent\Entity\SpentInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class SpentController.
 *
 *  Returns responses for Spent routes.
 */
class SpentController extends ControllerBase implements ContainerInjectionInterface {


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
   * Constructs a new SpentController.
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
   * Displays a Spent revision.
   *
   * @param int $spent_revision
   *   The Spent revision ID.
   *
   * @return array
   *   An array suitable for drupal_render().
   */
  public function revisionShow($spent_revision) {
    $spent = $this->entityTypeManager()->getStorage('spent')
      ->loadRevision($spent_revision);
    $view_builder = $this->entityTypeManager()->getViewBuilder('spent');

    return $view_builder->view($spent);
  }

  /**
   * Page title callback for a Spent revision.
   *
   * @param int $spent_revision
   *   The Spent revision ID.
   *
   * @return string
   *   The page title.
   */
  public function revisionPageTitle($spent_revision) {
    $spent = $this->entityTypeManager()->getStorage('spent')
      ->loadRevision($spent_revision);
    return $this->t('Revision of %title from %date', [
      '%title' => $spent->label(),
      '%date' => $this->dateFormatter->format($spent->getRevisionCreationTime()),
    ]);
  }

  /**
   * Generates an overview table of older revisions of a Spent.
   *
   * @param \Drupal\cashme_spent\Entity\SpentInterface $spent
   *   A Spent object.
   *
   * @return array
   *   An array as expected by drupal_render().
   */
  public function revisionOverview(SpentInterface $spent) {
    $account = $this->currentUser();
    $spent_storage = $this->entityTypeManager()->getStorage('spent');

    $langcode = $spent->language()->getId();
    $langname = $spent->language()->getName();
    $languages = $spent->getTranslationLanguages();
    $has_translations = (count($languages) > 1);
    $build['#title'] = $has_translations ? $this->t('@langname revisions for %title', ['@langname' => $langname, '%title' => $spent->label()]) : $this->t('Revisions for %title', ['%title' => $spent->label()]);

    $header = [$this->t('Revision'), $this->t('Operations')];
    $revert_permission = (($account->hasPermission("revert all spent revisions") || $account->hasPermission('administer spent entities')));
    $delete_permission = (($account->hasPermission("delete all spent revisions") || $account->hasPermission('administer spent entities')));

    $rows = [];

    $vids = $spent_storage->revisionIds($spent);

    $latest_revision = TRUE;

    foreach (array_reverse($vids) as $vid) {
      /** @var \Drupal\cashme_spent\SpentInterface $revision */
      $revision = $spent_storage->loadRevision($vid);
      // Only show revisions that are affected by the language that is being
      // displayed.
      if ($revision->hasTranslation($langcode) && $revision->getTranslation($langcode)->isRevisionTranslationAffected()) {
        $username = [
          '#theme' => 'username',
          '#account' => $revision->getRevisionUser(),
        ];

        // Use revision link to link to revisions that are not active.
        $date = $this->dateFormatter->format($revision->getRevisionCreationTime(), 'short');
        if ($vid != $spent->getRevisionId()) {
          $link = $this->l($date, new Url('entity.spent.revision', [
            'spent' => $spent->id(),
            'spent_revision' => $vid,
          ]));
        }
        else {
          $link = $spent->link($date);
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
              Url::fromRoute('entity.spent.translation_revert', [
                'spent' => $spent->id(),
                'spent_revision' => $vid,
                'langcode' => $langcode,
              ]) :
              Url::fromRoute('entity.spent.revision_revert', [
                'spent' => $spent->id(),
                'spent_revision' => $vid,
              ]),
            ];
          }

          if ($delete_permission) {
            $links['delete'] = [
              'title' => $this->t('Delete'),
              'url' => Url::fromRoute('entity.spent.revision_delete', [
                'spent' => $spent->id(),
                'spent_revision' => $vid,
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

    $build['spent_revisions_table'] = [
      '#theme' => 'table',
      '#rows' => $rows,
      '#header' => $header,
    ];

    return $build;
  }

}
