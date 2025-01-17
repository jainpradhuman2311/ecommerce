<?php

namespace Drupal\core_views_facets\Plugin\facets\url_processor;

use Drupal\Core\Cache\UnchangingCacheableDependencyTrait;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\core_views_facets\Plugin\facets\facet_source\CoreViewsContextualFilter;
use Drupal\core_views_facets\Plugin\facets\facet_source\CoreViewsExposedFilter;
use Drupal\facets\FacetInterface;
use Drupal\facets\UrlProcessor\UrlProcessorPluginBase;
use Drupal\views\ViewExecutableFactory;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * A url processor for views exposed filters.
 *
 * The core_views_facets url processor builds urls as the views exposed filters
 * expect them. We have to adhere to the urls that views uses to be able to use
 * the processing trough views.
 *
 * @FacetsUrlProcessor(
 *   id = "core_views_url_processor",
 *   label = @Translation("Core views url processor"),
 *   description = @Translation("Formats the query URL so views can process it."),
 * )
 */
class CoreViewsUrlProcessor extends UrlProcessorPluginBase {

  use UnchangingCacheableDependencyTrait;

  /**
   * The current view.
   *
   * @var \Drupal\views\ViewExecutable
   */
  protected $currentView;

  /**
   * The factory to load a view executable with.
   *
   * @var \Drupal\views\ViewExecutableFactory
   */
  protected $executableFactory;

  /**
   * The view storage.
   *
   * @var \Drupal\Core\Entity\EntityStorageInterface
   */
  protected $storage;

  /**
   * Constructs a new instance of the class.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Symfony\Component\HttpFoundation\Request $request
   *   A request object for the current request.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The Entity Type Manager.
   * @param \Drupal\views\ViewExecutableFactory $executable_factory
   *   The view executable factory.
   *
   * @throws \Drupal\facets\Exception\InvalidProcessorException
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, Request $request, EntityTypeManagerInterface $entity_type_manager, ViewExecutableFactory $executable_factory) {
    parent::__construct($configuration, $plugin_id, $plugin_definition, $request, $entity_type_manager);

    $this->executableFactory = $executable_factory;
    $this->storage = $entity_type_manager->getStorage('view');
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('request_stack')->getMainRequest(),
      $container->get('entity_type.manager'),
      $container->get('views.executable')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function buildUrls(FacetInterface $facet, array $results) {
    // No results are found for this facet, so don't try to create urls.
    if (empty($results)) {
      return [];
    }

    $source = $facet->getFacetSource();

    if (
      $source instanceof CoreViewsExposedFilter
      || $source instanceof CoreViewsContextualFilter
    ) {
      $map = $source->getViewsArgumentsMap();
      // Load the view.
      /** @var \Drupal\views\ViewEntityInterface $view */
      $view = $this->storage->load($source->pluginDefinition['view_id']);
      $this->currentView = $this->executableFactory->get($view);
      $this->currentView->setDisplay($source->pluginDefinition['view_display']);

      switch (TRUE) {
        case $source instanceof CoreViewsExposedFilter:
          // First get the current list of get parameters.
          $get_params = $this->request->query;

          $views_filter = $source->getViewsFilterDefinition($facet->getFieldIdentifier());
          $views_filter_parameter = empty($views_filter['expose']) ? $facet->getFieldIdentifier() : $views_filter['expose']['identifier'];

          /** @var \Drupal\facets\Result\ResultInterface $result */
          foreach ($results as &$result) {
            $result_get_params = clone $get_params;

            $active_values = $result_get_params->has($views_filter_parameter)
              ? $result_get_params->all()[$views_filter_parameter]
              : '';
            if ($active_values === '' && $views_filter['expose']['multiple']) {
              $active_values = [];
            }

            // If the value is active, remove the filter string from parameters.
            if ($result->isActive()) {
              if ($views_filter['expose']['multiple']) {
                if (($key = array_search($result->getRawValue(), $active_values)) !== FALSE) {
                  unset($active_values[$key]);
                }
              }
              else {
                if ($active_values == $result->getRawValue()) {
                  $active_values = '';
                }
              }
            }
            // If the value is not active, add the filter string.
            elseif ($views_filter['expose']['multiple']) {
              $active_values = [$result->getRawValue()];
            }
            else {
              $active_values = $result->getRawValue();
            }

            if (
              $active_values
              || $active_values == '0'
            ) {
              $result_get_params->set($views_filter_parameter, $active_values);
            }
            else {
              $result_get_params->remove($views_filter_parameter);
            }

            $request_arguments = [];
            foreach ($map as $views_argument_id => $current_argument) {
              $request_arguments[] = $current_argument['value'];
            }
            $url = $this->currentView->getUrl($request_arguments);

            // Add existing additional parameters, except pager.
            $additional_parameters = $result_get_params->all();
            unset($additional_parameters['page'], $additional_parameters['ajax_page_state'], $additional_parameters['_wrapper_format']);
            $url->setOption('query', $additional_parameters);

            $result->setUrl($url);
          }

          return $results;

        case $source instanceof CoreViewsContextualFilter:
          /** @var \Drupal\facets\Result\ResultInterface $result */
          foreach ($results as &$result) {
            $request_arguments = [];
            foreach ($map as $views_argument_id => $current_argument) {
              if ($views_argument_id == $facet->getFieldIdentifier()) {
                $request_arguments[] = $result->isActive() ? $current_argument['neutral_value'] : $result->getRawValue();
              }
              else {
                $request_arguments[] = $current_argument['value'];
              }
            }

            $url = $this->currentView->getUrl($request_arguments);

            // Add existing additional parameters, except pager.
            $additional_parameters = $this->request->query->all();
            unset($additional_parameters['page'], $additional_parameters['ajax_page_state'], $additional_parameters['_wrapper_format']);
            $url->setOption('query', $additional_parameters);

            $result->setUrl($url);
          }

          return $results;

        default:
          return [];
      }
    }

    return [];
  }

  /**
   * {@inheritdoc}
   */
  public function setActiveItems(FacetInterface $facet) {
    $source = $facet->getFacetSource();

    switch (TRUE) {
      case $source instanceof CoreViewsExposedFilter:

        $views_filter = $source->getViewsFilterDefinition($facet->getFieldIdentifier());
        $views_filter_parameter = empty($views_filter['expose']) ? $facet->getFieldIdentifier() : $views_filter['expose']['identifier'];

        if ($this->request->query->has($views_filter_parameter)) {
          if ($views_filter['expose']['multiple']) {
            $views_filter_parameter_query = $this->request->query->all()[$views_filter_parameter] ?? [];
            foreach ($views_filter_parameter_query as $value) {
              $facet->setActiveItem(trim((string) $value, '"'));
            }
          }
          else {
            $value = $this->request->query->all()[$views_filter_parameter] ?? NULL;
            if (isset($value) && $value !== '') {
              $facet->setActiveItem($value);
            }
          }
        }

        break;

      case $source instanceof CoreViewsContextualFilter:
        $map = $source->getViewsArgumentsMap();

        if (isset($map[$facet->getFieldIdentifier()])) {
          $current_argument = $map[$facet->getFieldIdentifier()];
          if ($current_argument['value'] != $current_argument['neutral_value']) {
            $facet->setActiveItem($current_argument['value']);
          }
        }
        break;

      default:
        return;
    }
  }

}
