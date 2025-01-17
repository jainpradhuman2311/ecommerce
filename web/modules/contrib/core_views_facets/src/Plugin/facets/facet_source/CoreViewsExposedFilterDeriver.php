<?php

namespace Drupal\core_views_facets\Plugin\facets\facet_source;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\StringTranslation\TranslationInterface;
use Drupal\facets\FacetSource\FacetSourceDeriverBase;
use Drupal\views\Plugin\views\query\Sql;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Derives a facet source plugin definition for views with exposed filters.
 */
class CoreViewsExposedFilterDeriver extends FacetSourceDeriverBase {

  /**
   * The base plugin ID.
   *
   * @var string
   */
  protected $basePluginId;

  /**
   * The view storage.
   *
   * @var \Drupal\Core\Entity\EntityStorageInterface
   */
  protected $viewStorage;

  /**
   * Constructs a FacetSource object.
   *
   * @param string $base_plugin_id
   *   The base plugin ID.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager.
   * @param \Drupal\Core\StringTranslation\TranslationInterface $string_translation
   *   The string translation service.
   *
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  public function __construct($base_plugin_id, EntityTypeManagerInterface $entity_type_manager, TranslationInterface $string_translation) {
    $this->basePluginId = $base_plugin_id;
    $this->setEntityTypeManager($entity_type_manager);
    $this->viewStorage = $this->entityTypeManager->getStorage('view');
    $this->setStringTranslation($string_translation);
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, $base_plugin_id) {
    return new static(
      $base_plugin_id,
      $container->get('entity_type.manager'),
      $container->get('string_translation')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getDerivativeDefinitions($base_plugin_definition) {
    if (!isset($this->derivatives[$this->basePluginId])) {
      $plugin_derivatives = [];

      /** @var \Drupal\views\Entity\View[] $all_views */
      $all_views = $this->viewStorage->loadMultiple();
      foreach ($all_views as $view) {
        // Do not return results for disabled views.
        if (!$view->status()) {
          continue;
        }
        $displays = $view->get('display');
        foreach ($displays as $name => $display_info) {
          if ($display_info['display_plugin'] === 'page') {
            $view_executable = $view->getExecutable();
            $view_executable->setDisplay($name);
            if ($view_executable && $view_executable->getQuery() instanceof Sql) {
              $exposed_filter_available = FALSE;
              foreach ($view_executable->getHandlers('filter', $name) as $filter) {
                if (!empty($filter['exposed']) && $filter['exposed'] == TRUE) {
                  $exposed_filter_available = TRUE;
                }
              }

              if (!$exposed_filter_available) {
                continue;
              }

              $machine_name = $view->id() . '__' . $name;

              $plugin_derivatives[$machine_name] = [
                'id' => $this->basePluginId . ':' . $machine_name,
                'label' => $this->t('Core view exposed filter: %view_name, display: %display_title', [
                  '%view_name' => $view->label(),
                  '%display_title' => $display_info['display_title'],
                ]),
                'description' => $this->t('Provides a facet source.'),
                'display_id' => $machine_name,
                'config_dependencies' => [
                  'config' => [
                    $view->getConfigDependencyName(),
                  ],
                ],
                'view_id' => $view->id(),
                'view_display' => $name,
              ] + $base_plugin_definition;
            }
          }
        }
      }

      $this->derivatives[$this->basePluginId] = $plugin_derivatives;
    }
    return $this->derivatives[$this->basePluginId];
  }

}
