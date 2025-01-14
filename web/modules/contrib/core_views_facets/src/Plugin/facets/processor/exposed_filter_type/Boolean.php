<?php

namespace Drupal\core_views_facets\Plugin\facets\processor\exposed_filter_type;

use Drupal\Core\Cache\UnchangingCacheableDependencyTrait;
use Drupal\Core\Logger\RfcLogLevel;
use Drupal\Core\Utility\Error;
use Drupal\core_views_facets\CoreViewsFacetsFilterType;
use Drupal\facets\FacetInterface;
use Drupal\views\Plugin\views\HandlerBase;
use Drupal\views\ViewExecutable;

/**
 * A generic filter type for core views.
 *
 * @CoreViewsFacetsExposedFilterType(
 *   id = "boolean",
 *   label = "Boolean solution"
 * )
 */
class Boolean extends CoreViewsFacetsFilterType {

  use UnchangingCacheableDependencyTrait;

  /**
   * {@inheritdoc}
   */
  public function prepareQuery(ViewExecutable $view, HandlerBase $handler, FacetInterface $facet) {
    try {
      return parent::prepareQuery($view, $handler, $facet);
    }
    catch (\Exception $e) {
      Error::logException(
        \Drupal::logger('facets'),
        $e,
        t('The core_views_facets module tried at least once to generically handle the unknown views filter type %filter_type and failed.'),
        ['%filter_type' => $handler->pluginId],
        RfcLogLevel::NOTICE
      );
      return NULL;
    }

  }

  /**
   * {@inheritdoc}
   */
  public function processDatabaseRow(\stdClass $row, HandlerBase $handler, FacetInterface $facet) {
    $result = parent::processDatabaseRow($row, $handler, $facet);
    $result->setDisplayValue($result->getRawValue());

    return $result;
  }

}
