<?php

namespace Drupal\Tests\commerce_shipping\FunctionalJavascript;

use Drupal\commerce_checkout\Entity\CheckoutFlow;
use Drupal\commerce_order\Entity\Order;
use Drupal\commerce_order\Entity\OrderType;
use Drupal\commerce_payment\Entity\PaymentGateway;
use Drupal\commerce_price\Price;
use Drupal\commerce_product\Entity\ProductVariationType;
use Drupal\commerce_promotion\Entity\Promotion;
use Drupal\commerce_shipping\Entity\ShipmentType;
use Drupal\field\Entity\FieldConfig;
use Drupal\field\Entity\FieldStorageConfig;
use Drupal\profile\Entity\Profile;
use Drupal\profile\Entity\ProfileType;
use Drupal\Tests\commerce\FunctionalJavascript\CommerceWebDriverTestBase;
use Drupal\Tests\commerce_shipping\Traits\ShippingTestHelperTrait;

// cspell:ignore Hauptstrasse Sentier sentier

/**
 * Tests the "Shipping information" checkout pane.
 *
 * @group commerce_shipping
 */
class CheckoutPaneTest extends CommerceWebDriverTestBase {

  use ShippingTestHelperTrait;

  /**
   * First sample product.
   *
   * @var \Drupal\commerce_product\Entity\ProductInterface
   */
  protected $firstProduct;

  /**
   * Second sample product.
   *
   * @var \Drupal\commerce_product\Entity\ProductInterface
   */
  protected $secondProduct;

  /**
   * First shipping method.
   *
   * @var \Drupal\commerce_shipping\Entity\ShippingMethodInterface
   */
  protected $firstShippingMethod;

  /**
   * Second shipping method.
   *
   * @var \Drupal\commerce_shipping\Entity\ShippingMethodInterface
   */
  protected $secondShippingMethod;

  /**
   * The default profile's address.
   *
   * @var array
   */
  protected $defaultAddress = [
    'country_code' => 'US',
    'administrative_area' => 'SC',
    'locality' => 'Greenville',
    'postal_code' => '29616',
    'address_line1' => '9 Drupal Ave',
    'given_name' => 'Bryan',
    'family_name' => 'Centarro',
  ];

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'commerce_payment',
    'commerce_payment_example',
    'commerce_promotion',
    'commerce_shipping_test',
    'commerce_tax',
    'telephone',
  ];

  /**
   * {@inheritdoc}
   */
  protected function getAdministratorPermissions() {
    return array_merge([
      'access checkout',
    ], parent::getAdministratorPermissions());
  }

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();

    // Limit the available countries.
    $this->store->shipping_countries = ['US', 'FR', 'DE'];
    $this->store->save();

    /** @var \Drupal\commerce_payment\Entity\PaymentGateway $gateway */
    $gateway = PaymentGateway::create([
      'id' => 'example_onsite',
      'label' => 'Example',
      'plugin' => 'example_onsite',
    ]);
    $gateway->getPlugin()->setConfiguration([
      'api_key' => '2342',
      'payment_method_types' => ['credit_card'],
    ]);
    $gateway->save();

    $product_variation_type = ProductVariationType::load('default');
    $product_variation_type->setTraits(['purchasable_entity_shippable']);
    $product_variation_type->save();

    $order_type = OrderType::load('default');
    $order_type->setThirdPartySetting('commerce_checkout', 'checkout_flow', 'shipping');
    $order_type->setThirdPartySetting('commerce_shipping', 'shipment_type', 'default');
    $order_type->save();

    // Create the order field.
    $field_definition = commerce_shipping_build_shipment_field_definition($order_type->id());
    $this->container->get('commerce.configurable_field_manager')->createField($field_definition);

    // Install the variation trait.
    $trait_manager = $this->container->get('plugin.manager.commerce_entity_trait');
    $trait = $trait_manager->createInstance('purchasable_entity_shippable');
    $trait_manager->installTrait($trait, 'commerce_product_variation', 'default');

    // Create two products.
    $variation = $this->createEntity('commerce_product_variation', [
      'type' => 'default',
      'sku' => strtolower($this->randomMachineName()),
      'price' => [
        'number' => '7.99',
        'currency_code' => 'USD',
      ],
    ]);
    /** @var \Drupal\commerce_product\Entity\ProductInterface $product */
    $this->firstProduct = $this->createEntity('commerce_product', [
      'type' => 'default',
      'title' => 'Conference hat',
      'variations' => [$variation],
      'stores' => [$this->store],
    ]);

    $variation = $this->createEntity('commerce_product_variation', [
      'type' => 'default',
      'sku' => strtolower($this->randomMachineName()),
      'price' => [
        'number' => '8.99',
        'currency_code' => 'USD',
      ],
    ]);
    /** @var \Drupal\commerce_product\Entity\ProductInterface $product */
    $this->secondProduct = $this->createEntity('commerce_product', [
      'type' => 'default',
      'title' => 'Conference bow tie',
      'variations' => [$variation],
      'stores' => [$this->store],
    ]);

    /** @var \Drupal\commerce_shipping\Entity\PackageType $package_type */
    $package_type = $this->createEntity('commerce_package_type', [
      'id' => 'package_type_a',
      'label' => 'Package Type A',
      'dimensions' => [
        'length' => 20,
        'width' => 20,
        'height' => 20,
        'unit' => 'mm',

      ],
      'weight' => [
        'number' => 20,
        'unit' => 'g',
      ],
    ]);
    $this->container->get('plugin.manager.commerce_package_type')->clearCachedDefinitions();

    // Create two flat rate shipping methods.
    $this->firstShippingMethod = $this->createEntity('commerce_shipping_method', [
      'name' => 'Overnight shipping',
      'stores' => [$this->store->id()],
      'plugin' => [
        'target_plugin_id' => 'flat_rate',
        'target_plugin_configuration' => [
          'default_package_type' => 'commerce_package_type:' . $package_type->get('uuid'),
          'rate_label' => 'Overnight shipping',
          'rate_description' => 'At your door tomorrow morning',
          'rate_amount' => [
            'number' => '19.99',
            'currency_code' => 'USD',
          ],
        ],
      ],
    ]);
    $this->secondShippingMethod = $this->createEntity('commerce_shipping_method', [
      'name' => 'Standard shipping',
      'stores' => [$this->store->id()],
      // Ensure that Standard shipping shows before overnight shipping.
      'weight' => -10,
      'plugin' => [
        'target_plugin_id' => 'flat_rate',
        'target_plugin_configuration' => [
          'rate_label' => 'Standard shipping',
          'rate_amount' => [
            'number' => '9.99',
            'currency_code' => 'USD',
          ],
        ],
      ],
    ]);
    $second_store = $this->createStore();
    // Should never be shown cause it doesn't belong to the order's store.
    $third_shipping_method = $this->createEntity('commerce_shipping_method', [
      'name' => 'Secret shipping',
      'stores' => [$second_store->id()],
      'plugin' => [
        'target_plugin_id' => 'flat_rate',
        'target_plugin_configuration' => [
          'rate_label' => 'Secret shipping',
          'rate_amount' => [
            'number' => '9.99',
            'currency_code' => 'USD',
          ],
        ],
      ],
    ]);

    $promotion = Promotion::create([
      'name' => 'Promotion 1',
      'order_types' => ['default'],
      'stores' => [$this->store->id()],
      'offer' => [
        'target_plugin_id' => 'shipment_fixed_amount_off',
        'target_plugin_configuration' => [
          'display_inclusive' => TRUE,
          'filter' => 'include',
          'shipping_methods' => [
            ['shipping_method' => $this->firstShippingMethod->uuid()],
          ],
          'amount' => [
            'number' => '3.00',
            'currency_code' => 'USD',
          ],
        ],
      ],
      'status' => TRUE,
    ]);
    $promotion->save();

    // Create a different shipping profile type, which also has a Phone field.
    $bundle_entity_duplicator = $this->container->get('entity.bundle_entity_duplicator');
    $customer_profile_type = ProfileType::load('customer');
    $shipping_profile_type = $bundle_entity_duplicator->duplicate($customer_profile_type, [
      'id' => 'customer_shipping',
      'label' => 'Customer (Shipping)',
    ]);
    // Add a telephone field to the new profile type.
    $field_storage = FieldStorageConfig::create([
      'field_name' => 'field_phone',
      'entity_type' => 'profile',
      'type' => 'telephone',
    ]);
    $field_storage->save();
    $field = FieldConfig::create([
      'field_storage' => $field_storage,
      'bundle' => $shipping_profile_type->id(),
      'label' => 'Phone',
    ]);
    $field->save();
    $form_display = commerce_get_entity_display('profile', 'customer_shipping', 'form');
    $form_display->setComponent('field_phone', [
      'type' => 'telephone_default',
    ]);
    $form_display->save();
    $view_display = commerce_get_entity_display('profile', 'customer_shipping', 'view');
    $view_display->setComponent('field_phone', [
      'type' => 'basic_string',
    ]);
    $view_display->save();

    $checkout_flow = CheckoutFlow::load('shipping');
    $checkout_flow_configuration = $checkout_flow->get('configuration');
    $checkout_flow_configuration['panes']['shipping_information']['auto_recalculate'] = FALSE;
    $checkout_flow->set('configuration', $checkout_flow_configuration);
    $checkout_flow->save();
  }

  /**
   * Tests checkout with a single shipment.
   */
  public function testSingleShipment() {
    // Create a default profile to confirm that it is used at checkout.
    $default_profile = $this->createEntity('profile', [
      'type' => 'customer',
      'uid' => $this->adminUser->id(),
      'address' => $this->defaultAddress,
    ]);

    $this->drupalGet($this->firstProduct->toUrl()->toString());
    $this->submitForm([], 'Add to cart');
    $this->drupalGet($this->secondProduct->toUrl()->toString());
    $this->submitForm([], 'Add to cart');

    $this->drupalGet('checkout/1');
    $this->assertSession()->pageTextContains('Shipping information');
    $this->assertRenderedAddress($this->defaultAddress, 'shipping_information[shipping_profile]');
    $this->assertRenderedAddress($this->defaultAddress, 'payment_information[add_payment_method][billing_information]');

    // Confirm that shipping method selection is available, because the
    // selected profile has a complete address.
    $this->assertSession()->pageTextContains('Shipping method');
    $page = $this->getSession()->getPage();
    $first_radio_button = $page->findField('Standard shipping: $9.99');
    // The $19.99 is displayed crossed out, but Mink strips HTML.
    $second_radio_button = $page->findField('Overnight shipping: $19.99 $16.99');
    $this->assertNotNull($first_radio_button);
    $this->assertNotNull($second_radio_button);
    $this->assertNotEmpty($first_radio_button->getAttribute('checked'));

    // Confirm that the description for overnight shipping is shown.
    $this->assertSession()->pageTextContains('At your door tomorrow morning');
    $selector = '//div[@data-drupal-selector="edit-order-summary"]';
    $this->assertSession()->elementTextContains('xpath', $selector, 'Shipping $9.99');
    $second_radio_button->click();
    $this->assertSession()->assertWaitOnAjaxRequest();
    $this->assertSession()->elementTextContains('xpath', $selector, 'Shipping $16.99');
    $first_radio_button->click();
    $this->assertSession()->assertWaitOnAjaxRequest();

    $this->submitForm([
      'payment_information[add_payment_method][payment_details][number]' => '4111111111111111',
      'payment_information[add_payment_method][payment_details][expiration][month]' => '02',
      'payment_information[add_payment_method][payment_details][expiration][year]' => $this->getCardExpirationYear(),
      'payment_information[add_payment_method][payment_details][security_code]' => '123',
    ], 'Continue to review');

    // Confirm that the review is rendered correctly.
    $this->assertSession()->pageTextContains('Shipping information');
    foreach ($this->defaultAddress as $property => $value) {
      if ($property != 'country_code') {
        $this->assertSession()->pageTextContains($value);
      }
    }
    $this->assertSession()->pageTextContains('Standard shipping');
    $this->assertSession()->pageTextNotContains('Secret shipping');
    $this->submitForm([], 'Pay and complete purchase');

    $order = Order::load(1);
    // Confirm the integrity of the shipment.
    $shipments = $order->get('shipments')->referencedEntities();
    $this->assertCount(1, $shipments);
    /** @var \Drupal\commerce_shipping\Entity\ShipmentInterface $shipment */
    $shipment = reset($shipments);
    $this->assertEquals('custom_box', $shipment->getPackageType()->getId());
    $this->assertEquals('Standard shipping', $shipment->getShippingMethod()->label());
    $this->assertEquals('default', $shipment->getShippingService());
    $this->assertEquals('9.99', $shipment->getAmount()->getNumber());
    $this->assertCount(2, $shipment->getItems());
    $this->assertEquals('draft', $shipment->getState()->value);
    // Confirm that the order total contains the shipment amount.
    $this->assertEquals(new Price('26.97', 'USD'), $order->getTotalPrice());
    // Confirm the integrity of the profiles.
    $billing_profile = $order->getBillingProfile();
    $shipping_profile = $shipment->getShippingProfile();
    $this->assertNotEquals($billing_profile->id(), $shipping_profile->id());
    $this->assertEquals($this->defaultAddress['address_line1'], $billing_profile->get('address')->address_line1);
    $this->assertEquals($this->defaultAddress['address_line1'], $shipping_profile->get('address')->address_line1);
    $this->assertEquals($default_profile->id(), $billing_profile->getData('address_book_profile_id'));
    $this->assertEquals($default_profile->id(), $shipping_profile->getData('address_book_profile_id'));
    $this->assertEmpty($billing_profile->getData('copy_to_address_book'));
    $this->assertEmpty($shipping_profile->getData('copy_to_address_book'));
  }

  /**
   * Tests checkout with multiple shipments.
   */
  public function testMultipleShipments() {
    // Create a default profile to confirm that it is used at checkout.
    $default_profile = $this->createEntity('profile', [
      'type' => 'customer',
      'uid' => $this->adminUser->id(),
      'address' => $this->defaultAddress,
    ]);

    $this->drupalGet($this->firstProduct->toUrl()->toString());
    $this->submitForm([], 'Add to cart');
    $this->drupalGet($this->secondProduct->toUrl()->toString());
    $this->submitForm([], 'Add to cart');

    $this->drupalGet('checkout/1');
    $this->assertSession()->pageTextContains('Shipping information');
    $this->assertRenderedAddress($this->defaultAddress, 'shipping_information[shipping_profile]');
    $this->assertRenderedAddress($this->defaultAddress, 'payment_information[add_payment_method][billing_information]');

    // Confirm that it is possible to enter a different address.
    $this->getSession()->getPage()->fillField('shipping_information[shipping_profile][select_address]', '_new');
    $this->assertSession()->assertWaitOnAjaxRequest();
    $address = [
      'given_name' => 'John',
      'family_name' => 'Smith',
      'address_line1' => '38 rue du sentier',
      'locality' => 'Paris',
      'postal_code' => '75002',
    ];
    $address_prefix = 'shipping_information[shipping_profile][address][0][address]';
    $page = $this->getSession()->getPage();
    $page->fillField($address_prefix . '[country_code]', 'FR');
    $this->assertSession()->assertWaitOnAjaxRequest();
    foreach ($address as $property => $value) {
      $page->fillField($address_prefix . '[' . $property . ']', $value);
    }
    $page->findButton('Recalculate shipping')->click();
    $this->assertSession()->assertWaitOnAjaxRequest();

    foreach ([0, 1] as $shipment_index) {
      $label_index = $shipment_index + 1;
      $this->assertSession()->pageTextContains('Shipment #' . $label_index);
      $first_radio_button = $page->findField('shipping_information[shipments][' . $shipment_index . '][shipping_method][0]');
      $second_radio_button = $page->findField('shipping_information[shipments][' . $shipment_index . '][shipping_method][0]');
      $this->assertNotNull($first_radio_button);
      $this->assertNotNull($second_radio_button);
      // The radio buttons don't have access to their own labels.
      $selector = '//fieldset[@data-drupal-selector="edit-shipping-information-shipments-0-shipping-method-0"]';
      $this->assertSession()->elementTextContains('xpath', $selector, 'Standard shipping: $9.99');
      // The $19.99 is displayed crossed out, but Mink strips HTML.
      $this->assertSession()->elementTextContains('xpath', $selector, 'Overnight shipping: $19.99 $16.99');
      $this->assertSession()->elementTextContains('xpath', $selector, 'At your door tomorrow morning');
    }
    $this->assertSession()->pageTextContains('Shipment #1 $9.99');
    $this->assertSession()->pageTextContains('Shipment #2 $9.99');
    $this->submitForm([
      'payment_information[add_payment_method][payment_details][number]' => '4111111111111111',
      'payment_information[add_payment_method][payment_details][expiration][month]' => '02',
      'payment_information[add_payment_method][payment_details][expiration][year]' => $this->getCardExpirationYear(),
      'payment_information[add_payment_method][payment_details][security_code]' => '123',
    ], 'Continue to review');

    // Confirm that the review is rendered correctly.
    $this->assertSession()->pageTextContains('Shipping information');
    foreach ($address as $property => $value) {
      $this->assertSession()->pageTextContains($value);
    }
    $this->assertSession()->pageTextContains('Shipment #1');
    $this->assertSession()->pageTextContains('Shipment #2');
    $this->assertSession()->elementsCount('xpath', '//div[contains(text(), "Standard shipping")]', 2);
    // Confirm the integrity of the shipment.
    $this->submitForm([], 'Pay and complete purchase');

    $order = Order::load(1);
    $billing_profile = $order->getBillingProfile();
    $this->assertNotEmpty($billing_profile);
    $this->assertEquals('customer', $billing_profile->bundle());
    $this->assertEquals('Paris', $billing_profile->get('address')->locality);

    // Confirm the integrity of the shipments.
    $shipments = $order->get('shipments')->referencedEntities();
    $this->assertCount(2, $shipments);
    /** @var \Drupal\commerce_shipping\Entity\ShipmentInterface $first_shipment */
    $first_shipment = reset($shipments);
    $this->assertEquals('custom_box', $first_shipment->getPackageType()->getId());
    $this->assertEquals('Paris', $first_shipment->getShippingProfile()->get('address')->locality);
    $this->assertEquals('Standard shipping', $first_shipment->getShippingMethod()->label());
    $this->assertEquals('default', $first_shipment->getShippingService());
    $this->assertEquals('9.99', $first_shipment->getAmount()->getNumber());
    $this->assertEquals('draft', $first_shipment->getState()->value);
    $this->assertCount(1, $first_shipment->getItems());
    $items = $first_shipment->getItems();
    $item = reset($items);
    $this->assertEquals('Conference hat', $item->getTitle());
    $this->assertEquals(1, $item->getQuantity());
    /** @var \Drupal\commerce_shipping\Entity\ShipmentInterface $second_shipment */
    $second_shipment = end($shipments);
    $this->assertEquals('custom_box', $second_shipment->getPackageType()->getId());
    $this->assertEquals('Paris', $second_shipment->getShippingProfile()->get('address')->locality);
    $this->assertEquals('Standard shipping', $second_shipment->getShippingMethod()->label());
    $this->assertEquals('default', $second_shipment->getShippingService());
    $this->assertEquals('9.99', $second_shipment->getAmount()->getNumber());
    $this->assertEquals('draft', $second_shipment->getState()->value);
    $this->assertCount(1, $second_shipment->getItems());
    $items = $second_shipment->getItems();
    $item = reset($items);
    $this->assertEquals('Conference bow tie', $item->getTitle());
    $this->assertEquals(1, $item->getQuantity());
    // Confirm that the shipping profile is shared between shipments.
    $this->assertEquals($first_shipment->getShippingProfile()->id(), $second_shipment->getShippingProfile()->id());
    // Confirm that the order total contains the shipment amounts.
    $this->assertEquals(new Price('36.96', 'USD'), $order->getTotalPrice());
  }

  /**
   * Tests checkout when the shipping profile is not required for showing rates.
   */
  public function testNoRequiredShippingProfile() {
    $checkout_flow = CheckoutFlow::load('shipping');
    $checkout_flow_configuration = $checkout_flow->get('configuration');
    $checkout_flow_configuration['panes']['shipping_information']['require_shipping_profile'] = FALSE;
    // Confirm that enabling the auto recalculation doesn't have any effect
    // when a shipping profile is not required.
    $checkout_flow_configuration['panes']['shipping_information']['auto_recalculate'] = TRUE;
    $checkout_flow->set('configuration', $checkout_flow_configuration);
    $checkout_flow->save();

    $this->drupalGet($this->firstProduct->toUrl()->toString());
    $this->submitForm([], 'Add to cart');
    $this->drupalGet($this->secondProduct->toUrl()->toString());
    $this->submitForm([], 'Add to cart');

    $this->drupalGet('checkout/1');
    // Confirm that the shipping methods are shown without a profile.
    $this->assertSession()->pageTextContains('Shipping method');
    $page = $this->getSession()->getPage();
    $first_radio_button = $page->findField('Standard shipping: $9.99');
    // The $19.99 is displayed crossed out, but Mink strips HTML.
    $second_radio_button = $page->findField('Overnight shipping: $19.99 $16.99');
    $this->assertNotNull($first_radio_button);
    $this->assertNotNull($second_radio_button);
    $this->assertNotEmpty($first_radio_button->getAttribute('checked'));
    // Confirm that the description for overnight shipping is shown.
    $this->assertSession()->pageTextContains('At your door tomorrow morning');
    $selector = '//div[@data-drupal-selector="edit-order-summary"]';
    $this->assertSession()->elementTextContains('xpath', $selector, 'Shipping $9.99');

    // Complete the order information step.
    $address = [
      'given_name' => 'John',
      'family_name' => 'Smith',
      'address_line1' => '1098 Alta Ave',
      'locality' => 'Mountain View',
      'administrative_area' => 'CA',
      'postal_code' => '94043',
    ];
    $address_prefix = 'shipping_information[shipping_profile][address][0][address]';
    // Confirm that the country list has been restricted.
    $this->assertOptions($address_prefix . '[country_code]', ['US', 'FR', 'DE']);
    $page->fillField($address_prefix . '[country_code]', 'US');
    foreach ($address as $property => $value) {
      $page->fillField($address_prefix . '[' . $property . ']', $value);
    }
    $this->submitForm([
      'payment_information[add_payment_method][payment_details][number]' => '4111111111111111',
      'payment_information[add_payment_method][payment_details][expiration][month]' => '02',
      'payment_information[add_payment_method][payment_details][expiration][year]' => $this->getCardExpirationYear(),
      'payment_information[add_payment_method][payment_details][security_code]' => '123',
    ], 'Continue to review');

    // Confirm that the review is rendered correctly.
    $this->assertSession()->pageTextContains('Shipping information');
    foreach ($address as $property => $value) {
      $this->assertSession()->pageTextContains($value);
    }
    $this->assertSession()->pageTextContains('Standard shipping');
    $this->submitForm([], 'Pay and complete purchase');

    $order = Order::load(1);
    // Confirm the integrity of the shipment.
    $shipments = $order->get('shipments')->referencedEntities();
    $this->assertCount(1, $shipments);
    /** @var \Drupal\commerce_shipping\Entity\ShipmentInterface $shipment */
    $shipment = reset($shipments);
    $this->assertEquals('custom_box', $shipment->getPackageType()->getId());
    $this->assertEquals('Mountain View', $shipment->getShippingProfile()->get('address')->locality);
    $this->assertEquals('Standard shipping', $shipment->getShippingMethod()->label());
    $this->assertEquals('default', $shipment->getShippingService());
    $this->assertEquals('9.99', $shipment->getAmount()->getNumber());
    $this->assertCount(2, $shipment->getItems());
    $this->assertEquals('draft', $shipment->getState()->value);
    // Confirm that the order total contains the shipment amounts.
    $this->assertEquals(new Price('26.97', 'USD'), $order->getTotalPrice());
  }

  /**
   * Test checkout with a custom shipping profile type.
   */
  public function testCustomShippingProfileType() {
    $shipment_type = ShipmentType::load('default');
    $shipment_type->setProfileTypeId('customer_shipping');
    $shipment_type->save();

    $this->drupalGet($this->firstProduct->toUrl()->toString());
    $this->submitForm([], 'Add to cart');
    $this->drupalGet('checkout/1');
    $this->getSession()->getPage()->uncheckField('payment_information[add_payment_method][billing_information][copy_fields][enable]');
    $this->assertSession()->assertWaitOnAjaxRequest();

    $this->assertSession()->pageTextContains('Shipping information');
    // Confirm that the phone field is present, but only for shipping.
    $this->assertSession()->fieldExists('shipping_information[shipping_profile][field_phone][0][value]');
    $this->assertSession()->fieldNotExists('payment_information[add_payment_method][billing_information][field_phone][0][value]');

    $address = [
      'given_name' => 'John',
      'family_name' => 'Smith',
      'address_line1' => '1098 Alta Ave',
      'locality' => 'Mountain View',
      'administrative_area' => 'CA',
      'postal_code' => '94043',
    ];
    $address_prefix = 'shipping_information[shipping_profile][address][0][address]';
    // Confirm that the country list has been restricted.
    $this->assertOptions($address_prefix . '[country_code]', ['US', 'FR', 'DE']);
    $page = $this->getSession()->getPage();
    $page->fillField($address_prefix . '[country_code]', 'US');
    foreach ($address as $property => $value) {
      $page->fillField($address_prefix . '[' . $property . ']', $value);
    }
    $page->fillField('shipping_information[shipping_profile][field_phone][0][value]', '202-555-0108');
    // Confirm that the shipping method selection was not initially available
    // because there was no address known.
    $this->assertSession()->pageTextNotContains('Shipping method');
    $page->findButton('Recalculate shipping')->click();
    $this->assertSession()->assertWaitOnAjaxRequest();
    $this->assertSession()->pageTextContains('Shipping method');
    $first_radio_button = $page->findField('Standard shipping: $9.99');
    $this->assertNotNull($first_radio_button);
    $this->assertNotEmpty($first_radio_button->getAttribute('checked'));
    $selector = '//div[@data-drupal-selector="edit-order-summary"]';
    $this->assertSession()->elementTextContains('xpath', $selector, 'Shipping $9.99');

    $this->submitForm([
      'payment_information[add_payment_method][payment_details][number]' => '4111111111111111',
      'payment_information[add_payment_method][payment_details][expiration][month]' => '02',
      'payment_information[add_payment_method][payment_details][expiration][year]' => $this->getCardExpirationYear(),
      'payment_information[add_payment_method][payment_details][security_code]' => '123',
      'payment_information[add_payment_method][billing_information][address][0][address][given_name]' => 'Johnny',
      'payment_information[add_payment_method][billing_information][address][0][address][family_name]' => 'Appleseed',
      'payment_information[add_payment_method][billing_information][address][0][address][address_line1]' => '123 New York Drive',
      'payment_information[add_payment_method][billing_information][address][0][address][locality]' => 'New York City',
      'payment_information[add_payment_method][billing_information][address][0][address][administrative_area]' => 'NY',
      'payment_information[add_payment_method][billing_information][address][0][address][postal_code]' => '10001',
    ], 'Continue to review');

    // Confirm that the review is rendered correctly.
    $this->assertSession()->pageTextContains('Shipping information');
    foreach ($address as $property => $value) {
      $this->assertSession()->pageTextContains($value);
    }
    $this->assertSession()->pageTextContains('202-555-0108');
    $this->assertSession()->pageTextContains('Standard shipping');
    $this->submitForm([], 'Pay and complete purchase');

    $order = Order::load(1);
    $billing_profile = $order->getBillingProfile();
    $this->assertNotEmpty($billing_profile);
    $this->assertEquals('customer', $billing_profile->bundle());
    $this->assertEquals('123 New York Drive', $billing_profile->get('address')->address_line1);
    // Confirm that the billing profile was copied to the address book.
    $address_book_profile_id = $billing_profile->getData('address_book_profile_id');
    $this->assertNotEmpty($address_book_profile_id);
    $address_book_profile = Profile::load($address_book_profile_id);
    $this->assertNotEmpty($address_book_profile);
    $this->assertTrue($address_book_profile->isDefault());
    $this->assertEquals('123 New York Drive', $address_book_profile->get('address')->address_line1);

    // Confirm the integrity of the shipment.
    $shipments = $order->get('shipments')->referencedEntities();
    $this->assertCount(1, $shipments);
    /** @var \Drupal\commerce_shipping\Entity\ShipmentInterface $shipment */
    $shipment = reset($shipments);
    $shipping_profile = $shipment->getShippingProfile();
    $this->assertNotEmpty($shipping_profile);
    $this->assertEquals('customer_shipping', $shipping_profile->bundle());
    $this->assertEquals('1098 Alta Ave', $shipping_profile->get('address')->address_line1);
    $this->assertEquals('202-555-0108', $shipping_profile->get('field_phone')->value);
    // Confirm that the shipping profile was copied to the address book.
    $address_book_profile_id = $shipping_profile->getData('address_book_profile_id');
    $this->assertNotEmpty($address_book_profile_id);
    $address_book_profile = Profile::load($address_book_profile_id);
    $this->assertNotEmpty($address_book_profile);
    $this->assertTrue($address_book_profile->isDefault());
    $this->assertEquals('1098 Alta Ave', $address_book_profile->get('address')->address_line1);
    $this->assertEquals('202-555-0108', $address_book_profile->get('field_phone')->value);
  }

  /**
   * Tests checkout when no shipping options are available.
   *
   * It should prevent continuation but not crash.
   */
  public function testNoShippingOptions() {
    $third_store = $this->createStore();

    $variation = $this->createEntity('commerce_product_variation', [
      'type' => 'default',
      'sku' => strtolower($this->randomMachineName()),
      'price' => [
        'number' => '8.99',
        'currency_code' => 'USD',
      ],
    ]);
    /** @var \Drupal\commerce_product\Entity\ProductInterface $product */
    $third_product = $this->createEntity('commerce_product', [
      'type' => 'default',
      'title' => 'Conference bow tie',
      'variations' => [$variation],
      'stores' => [$third_store],
    ]);

    $checkout_flow = CheckoutFlow::load('shipping');
    $checkout_flow_configuration = $checkout_flow->get('configuration');
    $checkout_flow_configuration['panes']['shipping_information']['require_shipping_profile'] = FALSE;
    $checkout_flow->set('configuration', $checkout_flow_configuration);
    $checkout_flow->save();

    $this->drupalGet($third_product->toUrl()->toString());
    $this->submitForm([], 'Add to cart');

    $this->drupalGet('checkout/1');
    $this->assertSession()->pageTextContains('There are no shipping rates available for this address.');
    $page = $this->getSession()->getPage();

    // Complete the order information step.
    $address = [
      'given_name' => 'John',
      'family_name' => 'Smith',
      'address_line1' => '1098 Alta Ave',
      'locality' => 'Mountain View',
      'administrative_area' => 'CA',
      'postal_code' => '94043',
    ];
    $address_prefix = 'shipping_information[shipping_profile][address][0][address]';
    $page->fillField($address_prefix . '[country_code]', 'US');
    foreach ($address as $property => $value) {
      $page->fillField($address_prefix . '[' . $property . ']', $value);
    }
    $this->submitForm([
      'payment_information[add_payment_method][payment_details][number]' => '4111111111111111',
      'payment_information[add_payment_method][payment_details][expiration][month]' => '02',
      'payment_information[add_payment_method][payment_details][expiration][year]' => $this->getCardExpirationYear(),
      'payment_information[add_payment_method][payment_details][security_code]' => '123',
    ], 'Continue to review');

    $this->assertSession()->pageTextContains('A valid shipping method must be selected in order to check out.');
  }

  /**
   * Tests the change of shipping rate options on checkout.
   *
   * @dataProvider shippingMethodOptionChangeProvider
   */
  public function testShippingMethodOptionChanges($auto_recalculate) {
    $checkout_flow = CheckoutFlow::load('shipping');
    $checkout_flow_configuration = $checkout_flow->get('configuration');
    $checkout_flow_configuration['panes']['shipping_information']['auto_recalculate'] = $auto_recalculate;
    $checkout_flow->set('configuration', $checkout_flow_configuration);
    $checkout_flow->save();
    $address_fr = [
      'given_name' => 'John',
      'family_name' => 'Smith',
      'address_line1' => '38 rue du sentier',
      'locality' => 'Paris',
      'postal_code' => '75002',
      'country_code' => 'FR',
    ];
    $default_profile = $this->createEntity('profile', [
      'type' => 'customer',
      'uid' => $this->adminUser->id(),
      'address' => $this->defaultAddress,
      'is_default' => TRUE,
    ]);
    $another_profile = $this->createEntity('profile', [
      'type' => 'customer',
      'uid' => $this->adminUser->id(),
      'address' => $address_fr,
    ]);
    $conditions = [
      'target_plugin_id' => 'shipment_address',
      'target_plugin_configuration' => [
        'zone' => [
          'territories' => [
            [
              'country_code' => 'US',
            ],
          ],
        ],
      ],
    ];
    $this->firstShippingMethod->set('conditions', $conditions)->save();
    $conditions['target_plugin_configuration']['zone']['territories'][0]['country_code'] = 'FR';
    $this->secondShippingMethod->set('conditions', $conditions)->save();
    // Check if the shipping adjustment is removed in the summary.
    $order_summary_selector = '//div[@data-drupal-selector="edit-order-summary"]';

    $this->drupalGet($this->firstProduct->toUrl()->toString());
    $this->submitForm([], 'Add to cart');
    $this->drupalGet('checkout/1');
    $this->assertRenderedAddress($this->defaultAddress, 'shipping_information[shipping_profile]');
    $this->assertSession()->pageTextContains('Overnight shipping: $19.99 $16.99');
    $this->assertSession()->pageTextNotContains('Standard shipping');
    $this->getSession()->getPage()->fillField('shipping_information[shipping_profile][select_address]', 2);
    $this->assertSession()->assertWaitOnAjaxRequest();
    $this->assertRenderedAddress($address_fr, 'shipping_information[shipping_profile]');

    if (!$auto_recalculate) {
      $this->assertSession()->pageTextNotContains('An illegal choice has been detected. Please contact the site administrator.');
      $this->getSession()->getPage()->findButton('Recalculate shipping')->click();
      $this->assertSession()->assertWaitOnAjaxRequest();
    }
    else {
      $this->assertSession()->waitForText('Standard shipping');
    }
    $this->assertSession()->elementTextContains('xpath', $order_summary_selector, 'Shipping $9.99');
    $this->getSession()->getPage()->fillField('shipping_information[shipping_profile][select_address]', '_new');
    $this->assertSession()->assertWaitOnAjaxRequest();

    $address_de = [
      'given_name' => 'John',
      'family_name' => 'Smith',
      'address_line1' => 'Hauptstrasse 38',
      'locality' => 'Berlin',
      'postal_code' => '75002',
    ];
    $address_prefix = 'shipping_information[shipping_profile][address][0][address]';
    $this->getSession()->getPage()->fillField($address_prefix . '[country_code]', 'DE');
    $this->assertSession()->assertWaitOnAjaxRequest();
    foreach ($address_de as $property => $value) {
      $this->getSession()->getPage()->fillField($address_prefix . '[' . $property . ']', $value);
    }
    if (!$auto_recalculate) {
      $this->getSession()->getPage()->findButton('Recalculate shipping')->click();
      $this->assertSession()->assertWaitOnAjaxRequest();
    }
    else {
      $this->assertSession()->waitForText('There are no shipping rates available for this address.');
    }
    $this->assertSession()->elementTextNotContains('xpath', $order_summary_selector, 'Shipping $9.99');
    $this->assertSession()->pageTextContains('There are no shipping rates available for this address.');

    $this->assertSession()->pageTextNotContains('An illegal choice has been detected. Please contact the site administrator.');

    $address_us = [
      'given_name' => 'John',
      'family_name' => 'Smith',
      'address_line1' => 'Main street 38',
      'locality' => 'Los Angeles',
      'administrative_area' => 'CA',
      'postal_code' => '90014',
    ];
    $address_prefix = 'shipping_information[shipping_profile][address][0][address]';
    usleep(100000);
    $this->getSession()->getPage()->fillField($address_prefix . '[country_code]', 'US');
    $this->assertSession()->assertWaitOnAjaxRequest();
    foreach ($address_us as $property => $value) {
      $this->getSession()->getPage()->fillField($address_prefix . '[' . $property . ']', $value);
    }
    if (!$auto_recalculate) {
      $this->getSession()->getPage()->findButton('Recalculate shipping')->click();
      $this->assertSession()->assertWaitOnAjaxRequest();
    }
    else {
      $this->assertSession()->waitForText('Overnight shipping');
      usleep(100000);
    }
    $this->assertSession()->pageTextNotContains('An illegal choice has been detected. Please contact the site administrator.');
    $first_radio_button = $this->getSession()->getPage()->findField('Standard shipping: $9.99');
    $second_radio_button = $this->getSession()->getPage()->findField('Overnight shipping: $19.99 $16.99');
    $this->assertNull($first_radio_button);
    $this->assertNotNull($second_radio_button);
    $this->assertNotEmpty($second_radio_button->getAttribute('checked'));
    $selector = '//div[@data-drupal-selector="edit-order-summary"]';
    $this->assertSession()->elementTextContains('xpath', $selector, 'Shipping $16.99');

    $address_prefix = 'shipping_information[shipping_profile][address][0][address]';
    usleep(100000);
    $this->getSession()->getPage()->fillField($address_prefix . '[country_code]', 'FR');
    $this->assertSession()->assertWaitOnAjaxRequest();
    unset($address_fr['country_code']);
    foreach ($address_fr as $property => $value) {
      $this->getSession()->getPage()->fillField($address_prefix . '[' . $property . ']', $value);
    }
    if (!$auto_recalculate) {
      $this->getSession()->getPage()->findButton('Recalculate shipping')->click();
      $this->assertSession()->assertWaitOnAjaxRequest();
    }
    else {
      $this->assertSession()->waitForText('Standard shipping');
    }
    $this->assertSession()->pageTextNotContains('An illegal choice has been detected. Please contact the site administrator.');
    $first_radio_button = $this->getSession()->getPage()->findField('Standard shipping: $9.99');
    $second_radio_button = $this->getSession()->getPage()->findField('Overnight shipping: $19.99 $16.99');
    $this->assertNotNull($first_radio_button);
    $this->assertNull($second_radio_button);
    $this->assertNotEmpty($first_radio_button->getAttribute('checked'));
    $this->assertSession()->pageTextNotContains('At your door tomorrow morning');
  }

  /**
   * Data provider for ::testShippingMethodOptionChanges.
   *
   * @return array
   *   A list of testShippingMethodOptionChanges function arguments.
   */
  public static function shippingMethodOptionChangeProvider() {
    return [
      [FALSE],
      [TRUE],
    ];
  }

  /**
   * Tests that the order summary is properly refreshed.
   */
  public function testOrderSummaryRefresh() {
    // Create a tax type that applies to French addresses, to ensure the tax
    // adjustment is shown in the order summary when selecting the FR address.
    /** @var \Drupal\commerce_tax\Entity\TaxTypeInterface $tax_type */
    $this->store->set('tax_registrations', ['FR'])->save();
    $this->createEntity('commerce_tax_type', [
      'id' => 'fr_vat',
      'label' => 'French VAT',
      'plugin' => 'custom',
      'configuration' => [
        'display_inclusive' => TRUE,
        'display_label' => 'vat',
        'round' => TRUE,
        'rates' => [
          [
            'id' => 'standard',
            'label' => 'Standard',
            'percentage' => '0.2',
          ],
        ],
        'territories' => [
          ['country_code' => 'FR'],
        ],
      ],
      'status' => TRUE,
    ]);
    $address_fr = [
      'given_name' => 'John',
      'family_name' => 'Smith',
      'address_line1' => '38 rue du sentier',
      'locality' => 'Paris',
      'postal_code' => '75002',
      'country_code' => 'FR',
    ];
    $default_profile = $this->createEntity('profile', [
      'type' => 'customer',
      'uid' => $this->adminUser->id(),
      'address' => $this->defaultAddress,
      'is_default' => TRUE,
    ]);
    $another_profile = $this->createEntity('profile', [
      'type' => 'customer',
      'uid' => $this->adminUser->id(),
      'address' => $address_fr,
    ]);
    $this->drupalGet($this->firstProduct->toUrl()->toString());
    $this->submitForm([], 'Add to cart');
    $this->drupalGet('checkout/1');
    $this->assertRenderedAddress($this->defaultAddress, 'shipping_information[shipping_profile]');
    $this->assertSession()->pageTextContains('Overnight shipping: $19.99 $16.99');
    $this->assertSession()->pageTextContains('Standard shipping');
    $selector = '//div[@data-drupal-selector="edit-order-summary"]';
    $this->assertSession()->elementTextContains('xpath', $selector, 'Shipping $9.99');
    $second_radio_button = $this->getSession()->getPage()->findField('Overnight shipping: $19.99 $16.99');
    $second_radio_button->click();
    $this->assertSession()->assertWaitOnAjaxRequest();
    $this->assertSession()->elementTextContains('xpath', $selector, 'Shipping $16.99');
    $this->getSession()->getPage()->pressButton('shipping_edit');
    $this->assertSession()->assertWaitOnAjaxRequest();
    $this->getSession()->getPage()->fillField('shipping_information[shipping_profile][address][0][address][address_line1]', '9 Drupal Avenue');
    $this->getSession()->getPage()->findButton('Recalculate shipping')->click();
    $this->assertSession()->assertWaitOnAjaxRequest();
    $this->submitForm([
      'payment_information[add_payment_method][payment_details][number]' => '4111111111111111',
      'payment_information[add_payment_method][payment_details][expiration][month]' => '02',
      'payment_information[add_payment_method][payment_details][expiration][year]' => $this->getCardExpirationYear(),
      'payment_information[add_payment_method][payment_details][security_code]' => '123',
    ], 'Continue to review');
    $this->assertSession()->pageTextNotContains('The content has either been modified by another user');
    foreach ($this->defaultAddress as $property => $value) {
      if ($property != 'country_code') {
        $this->assertSession()->pageTextContains($value);
      }
    }
    $this->assertSession()->pageTextContains('Overnight shipping');
    $this->assertSession()->pageTextNotContains('Standard shipping');
    $conditions = [
      'target_plugin_id' => 'shipment_address',
      'target_plugin_configuration' => [
        'zone' => [
          'territories' => [
            [
              'country_code' => 'US',
            ],
          ],
        ],
      ],
    ];
    $this->firstShippingMethod->set('conditions', $conditions)->save();
    $conditions['target_plugin_configuration']['zone']['territories'][0]['country_code'] = 'FR';
    $this->secondShippingMethod->set('conditions', $conditions)->save();
    $this->assertSession()->pageTextContains('Shipping information');
    // Go back to the order info page, attempt switching between different
    // addresses.
    $this->getSession()->getPage()->clickLink('Go back');
    $this->getSession()->getPage()->fillField('shipping_information[shipping_profile][select_address]', 2);
    $this->assertSession()->assertWaitOnAjaxRequest();
    $this->assertRenderedAddress($address_fr, 'shipping_information[shipping_profile]');
    $this->assertSession()->elementTextContains('xpath', $selector, 'Shipping $9.99');
    // Ensure the tax is properly displayed in the summary when switching to the
    // FR address and assert that it's removed after switching back to the US
    // address.
    $this->assertSession()->elementTextContains('xpath', $selector, 'VAT $1.60');
    $this->getSession()->getPage()->fillField('shipping_information[shipping_profile][select_address]', 1);
    $this->assertSession()->assertWaitOnAjaxRequest();
    $this->assertSession()->elementTextNotContains('xpath', $selector, 'VAT $1.60');
    $this->getSession()->getPage()->fillField('shipping_information[shipping_profile][select_address]', 2);
    $this->assertSession()->assertWaitOnAjaxRequest();
    $this->assertSession()->elementTextContains('xpath', $selector, 'VAT $1.60');
    $this->submitForm([], 'Continue to review');
    $this->assertSession()->pageTextContains('Standard shipping');
    $this->assertSession()->pageTextNotContains('Overnight shipping');
    $this->assertSession()->elementTextContains('xpath', $selector, 'VAT $1.60');
  }

  /**
   * Tests the payment gateway filtering by shipping method.
   */
  public function testPaymentGatewayCondition() {
    $checkout_flow = CheckoutFlow::load('shipping');
    $checkout_flow_configuration = $checkout_flow->get('configuration');
    $checkout_flow_configuration['panes']['payment_information'] = [
      'step' => 'review',
      'weight' => 3,
    ];
    $checkout_flow->set('configuration', $checkout_flow_configuration);
    $checkout_flow->save();

    $payment_gateway = PaymentGateway::create([
      'id' => 'cash_on_delivery',
      'label' => 'Manual',
      'plugin' => 'manual',
      'configuration' => [
        'display_label' => 'Cash on delivery',
        'instructions' => [
          'value' => 'Sample payment instructions.',
          'format' => 'plain_text',
        ],
      ],
      'conditions' => [
        [
          'plugin' => 'order_shipping_method',
          'configuration' => [
            'shipping_methods' => [$this->secondShippingMethod->uuid()],
          ],
        ],
      ],
      'weight' => 10,
    ]);
    $payment_gateway->save();

    $this->drupalGet($this->firstProduct->toUrl()->toString());
    $this->submitForm([], 'Add to cart');
    $this->drupalGet('checkout/1');
    $page = $this->getSession()->getPage();

    $address = [
      'given_name' => 'John',
      'family_name' => 'Smith',
      'address_line1' => 'Hauptstrasse 38',
      'locality' => 'Berlin',
      'postal_code' => '75002',
    ];

    $address_prefix = 'shipping_information[shipping_profile][address][0][address]';
    $page->fillField($address_prefix . '[country_code]', 'DE');
    $this->assertSession()->assertWaitOnAjaxRequest();
    foreach ($address as $property => $value) {
      $page->fillField($address_prefix . '[' . $property . ']', $value);
    }
    $page->findButton('Recalculate shipping')->click();
    $this->assertSession()->assertWaitOnAjaxRequest();
    $page->selectFieldOption('shipping_information[shipments][0][shipping_method][0]', '2--default');
    $this->submitForm([], 'Continue to review');

    $this->assertSession()->pageTextContains('Credit card');
    $this->assertSession()->pageTextContains('Cash on delivery');

    $this->clickLink('Go back');
    $page->selectFieldOption('shipping_information[shipments][0][shipping_method][0]', '1--default');
    $this->assertSession()->pageTextNotContains('Cash on delivery');
  }

  /**
   * Tests that the shipping method doesn't change.
   */
  public function testSelectedShippingMethodPersists() {
    $this->createEntity('profile', [
      'type' => 'customer',
      'uid' => $this->adminUser->id(),
      'address' => $this->defaultAddress,
    ]);

    $this->drupalGet($this->firstProduct->toUrl()->toString());
    $this->submitForm([], 'Add to cart');
    $this->drupalGet($this->secondProduct->toUrl()->toString());
    $this->submitForm([], 'Add to cart');

    $this->drupalGet('checkout/1');
    $this->assertSession()->pageTextContains('Shipping information');
    $this->assertRenderedAddress($this->defaultAddress, 'shipping_information[shipping_profile]');
    $this->assertRenderedAddress($this->defaultAddress, 'payment_information[add_payment_method][billing_information]');

    // Confirm that shipping method selection is available, because the
    // selected profile has a complete address.
    $this->assertSession()->pageTextContains('Shipping method');
    $page = $this->getSession()->getPage();
    $first_radio_button = $page->findField('Standard shipping: $9.99');
    // The $19.99 is displayed crossed out, but Mink strips HTML.
    $second_radio_button = $page->findField('Overnight shipping: $19.99 $16.99');
    $this->assertNotNull($first_radio_button);
    $this->assertNotNull($second_radio_button);
    $this->assertNotEmpty($first_radio_button->getAttribute('checked'));

    $this->submitForm([
      'payment_information[add_payment_method][payment_details][number]' => '4111111111111111',
      'payment_information[add_payment_method][payment_details][expiration][month]' => '02',
      'payment_information[add_payment_method][payment_details][expiration][year]' => $this->getCardExpirationYear(),
      'payment_information[add_payment_method][payment_details][security_code]' => '123',
    ], 'Continue to review');
    $this->assertSession()->pageTextContains('Standard shipping');
    $this->assertSession()->pageTextNotContains('Overnight shipping');
    $page->clickLink('Go back');
    $first_radio_button = $page->findField('Standard shipping: $9.99');
    $second_radio_button = $page->findField('Overnight shipping: $19.99 $16.99');
    $this->assertNotNull($first_radio_button);
    $this->assertNotNull($second_radio_button);
    $this->assertNotEmpty($first_radio_button->getAttribute('checked'));
  }

  /**
   * Asserts that a select field has all of the provided options.
   *
   * Core only has assertOption(), this helper decreases the number of needed
   * assertions.
   *
   * @param string $id
   *   ID of select field to assert.
   * @param array $options
   *   Options to assert.
   * @param string $message
   *   (optional) A message to display with the assertion. Do not translate
   *   messages: use \Drupal\Component\Utility\SafeMarkup::format() to embed
   *   variables in the message text, not t(). If left blank, a default message
   *   will be displayed.
   */
  protected function assertOptions($id, array $options, $message = '') {
    $elements = $this->xpath('//select[@name="' . $id . '"]/option');
    $found_options = [];
    foreach ($elements as $element) {
      if ($option = $element->getValue()) {
        $found_options[] = $option;
      }
    }
    $this->assertFieldValues($found_options, $options, $message);
  }

}
