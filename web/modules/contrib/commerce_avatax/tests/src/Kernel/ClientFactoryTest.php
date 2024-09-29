<?php

namespace Drupal\Tests\commerce_avatax\Kernel;

use Drupal\commerce_avatax\ClientFactory;
use Drupal\KernelTests\KernelTestBase;

/**
 * Tests the API client factory.
 *
 * @group commerce_avatax
 */
class ClientFactoryTest extends KernelTestBase {

  /**
   * The tax type plugin.
   *
   * @var \Drupal\commerce_tax\Plugin\Commerce\TaxType\TaxTypeInterface
   */
  protected $plugin;

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'system',
    'commerce',
    'commerce_order',
    'commerce_price',
    'commerce_avatax',
  ];

  /**
   * Test that the Guzzle client is properly configured.
   */
  public function testClientFactory() {
    $client_factory = $this->container->get('commerce_avatax.client_factory');
    assert($client_factory instanceof ClientFactory);
    $config = [
      'account_id' => 'DUMMY ACCOUNT',
      'license_key' => 'DUMMY KEY',
      'api_mode' => 'development',
    ];
    $client_options = $client_factory->getClientOptions($config);
    $this->assertEquals('https://sandbox-rest.avatax.com/', $client_options['base_uri']);
    $headers = $client_options['headers'];
    $this->assertEquals('Basic ' . base64_encode($config['account_id'] . ':' . $config['license_key']), $headers['Authorization']);
    $this->assertEquals('a0o33000003waOC', $headers['x-Avalara-UID']);
    $server_machine_name = gethostname();
    $this->assertEquals("Drupal Commerce; Version [8.x-1.x]; REST; V2; [$server_machine_name]", $headers['x-Avalara-Client']);

    $config['api_mode'] = 'production';
    $client_options = $client_factory->getClientOptions($config);
    $this->assertEquals('https://rest.avatax.com/', $client_options['base_uri']);
  }

}
