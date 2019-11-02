<?php

namespace Drupal\cashme_incomes\Controller;

use Drupal\Component\Utility\Xss;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Datetime\DateFormatter;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Render\Renderer;
use Drupal\Core\Url;
use Drupal\cashme_incomes\Entity\IncomeInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class IncomeController.
 *
 *  Returns responses for Income routes.
 */
class IncomeController extends ControllerBase implements ContainerInjectionInterface {


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
   * Constructs a new IncomeController.
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
   * Displays a Income revision.
   *
   * @param int $income_revision
   *   The Income revision ID.
   *
   * @return array
   *   An array suitable for drupal_render().
   */
  public function revisionShow($income_revision) {
    $income = $this->entityTypeManager()->getStorage('income')
      ->loadRevision($income_revision);
    $view_builder = $this->entityTypeManager()->getViewBuilder('income');

    return $view_builder->view($income);
  }

  /**
   * Page title callback for a Income revision.
   *
   * @param int $income_revision
   *   The Income revision ID.
   *
   * @return string
   *   The page title.
   */
  public function revisionPageTitle($income_revision) {
    $income = $this->entityTypeManager()->getStorage('income')
      ->loadRevision($income_revision);
    return $this->t('Revision of %title from %date', [
      '%title' => $income->label(),
      '%date' => $this->dateFormatter->format($income->getRevisionCreationTime()),
    ]);
  }

  /**
   * Generates an overview table of older revisions of a Income.
   *
   * @param \Drupal\cashme_incomes\Entity\IncomeInterface $income
   *   A Income object.
   *
   * @return array
   *   An array as expected by drupal_render().
   */
  public function revisionOverview(IncomeInterface $income) {
    $account = $this->currentUser();
    $income_storage = $this->entityTypeManager()->getStorage('income');

    $langcode = $income->language()->getId();
    $langname = $income->language()->getName();
    $languages = $income->getTranslationLanguages();
    $has_translations = (count($languages) > 1);
    $build['#title'] = $has_translations ? $this->t('@langname revisions for %title', ['@langname' => $langname, '%title' => $income->label()]) : $this->t('Revisions for %title', ['%title' => $income->label()]);

    $header = [$this->t('Revision'), $this->t('Operations')];
    $revert_permission = (($account->hasPermission("revert all income revisions") || $account->hasPermission('administer income entities')));
    $delete_permission = (($account->hasPermission("delete all income revisions") || $account->hasPermission('administer income entities')));

    $rows = [];

    $vids = $income_storage->revisionIds($income);

    $latest_revision = TRUE;

    foreach (array_reverse($vids) as $vid) {
      /** @var \Drupal\cashme_incomes\IncomeInterface $revision */
      $revision = $income_storage->loadRevision($vid);
      // Only show revisions that are affected by the language that is being
      // displayed.
      if ($revision->hasTranslation($langcode) && $revision->getTranslation($langcode)->isRevisionTranslationAffected()) {
        $username = [
          '#theme' => 'username',
          '#account' => $revision->getRevisionUser(),
        ];

        // Use revision link to link to revisions that are not active.
        $date = $this->dateFormatter->format($revision->getRevisionCreationTime(), 'short');
        if ($vid != $income->getRevisionId()) {
          $link = $this->l($date, new Url('entity.income.revision', [
            'income' => $income->id(),
            'income_revision' => $vid,
          ]));
        }
        else {
          $link = $income->link($date);
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
              Url::fromRoute('entity.income.translation_revert', [
                'income' => $income->id(),
                'income_revision' => $vid,
                'langcode' => $langcode,
              ]) :
              Url::fromRoute('entity.income.revision_revert', [
                'income' => $income->id(),
                'income_revision' => $vid,
              ]),
            ];
          }

          if ($delete_permission) {
            $links['delete'] = [
              'title' => $this->t('Delete'),
              'url' => Url::fromRoute('entity.income.revision_delete', [
                'income' => $income->id(),
                'income_revision' => $vid,
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

    $build['income_revisions_table'] = [
      '#theme' => 'table',
      '#rows' => $rows,
      '#header' => $header,
    ];

    return $build;
  }

}
