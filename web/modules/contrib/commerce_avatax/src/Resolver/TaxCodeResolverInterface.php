<?php

namespace Drupal\commerce_avatax\Resolver;

use Drupal\commerce_order\Entity\OrderItemInterface;

/**
 * Defines interface for tax code resolvers.
 */
interface TaxCodeResolverInterface {

  /**
   * Resolves the tax code of a given order item..
   *
   * @param \Drupal\commerce_order\Entity\OrderItemInterface $order_item
   *   The purchaseable entity.
   *
   * @return string|null
   *   The tax code, NULL if it cannot be resolved.
   */
  public function resolve(OrderItemInterface $order_item);

}
