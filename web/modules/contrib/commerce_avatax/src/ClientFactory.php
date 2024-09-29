<?php

namespace Drupal\commerce_avatax;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Config\ImmutableConfig;
use Drupal\Core\Extension\ExtensionList;
use Drupal\Core\Http\ClientFactory as CoreClientFactory;

/**
 * API Client factory.
 */
class ClientFactory {

  /**
   * Static cache of instantiated clients.
   *
   * @var array
   */
  protected array $clients = [];

  /**
   * Constructs a new AvaTax ClientFactory object.
   *
   * @param \Drupal\Core\Http\ClientFactory $clientFactory
   *   The client factory.
   * @param \Drupal\Core\Extension\ExtensionList $extensionList
   *   The extension list.
   * @param \Drupal\Core\Config\ConfigFactoryInterface $configFactory
   *   The config factory.
   */
  public function __construct(protected CoreClientFactory $clientFactory, protected ExtensionList $extensionList, protected ConfigFactoryInterface $configFactory) {}

  /**
   * Gets an API client instance.
   *
   * @param array $config
   *   (optional) The client configuration, defaults to the global config.
   *
   * @return \GuzzleHttp\Client
   *   The API client.
   */
  public function createInstance(array $config = []) {
    $config = $this->getClientOptions($config);
    $client_hash = md5(json_encode($config));

    if (array_key_exists($client_hash, $this->clients)) {
      return $this->clients[$client_hash];
    }

    $this->clients[$client_hash] = $this->clientFactory->fromOptions($config);
    return $this->clients[$client_hash];
  }

  /**
   * Gets the client options.
   *
   * This was added because Guzzle 8.x deprecated the getConfig() method and
   * we no longer have a way to access the client configuration.
   *
   * @param array $config
   *   (optional) Defaults to the global configuration if not provided.
   *
   * @return array
   *   The client options.
   */
  public function getClientOptions(array $config = []): array {
    $config = !empty($config) ? $config : $this->getConfig()->get();
    $base_uri = match($config['api_mode']) {
      'production' => 'https://rest.avatax.com/',
      default => 'https://sandbox-rest.avatax.com/',
    };

    // Specify the x-Avalara-Client header.
    $server_machine_name = gethostname();
    $module_info = $this->extensionList->getExtensionInfo('commerce_avatax');
    $version = !empty($module_info['version']) ? $module_info['version'] : '8.x-1.x';
    $x_avalara_client = "Drupal Commerce; Version [$version]; REST; V2; [$server_machine_name]";

    return [
      'base_uri' => $base_uri,
      'headers' => [
        'Authorization' => 'Basic ' . base64_encode($config['account_id'] . ':' . $config['license_key']),
        'Content-Type' => 'application/json',
        'x-Avalara-UID' => 'a0o33000003waOC',
        'x-Avalara-Client' => $x_avalara_client,
      ],
    ];
  }

  /**
   * Gets the global configuration.
   *
   * @return \Drupal\Core\Config\ImmutableConfig
   *   The global configuration object.
   */
  protected function getConfig(): ImmutableConfig {
    return $this->configFactory->get('commerce_avatax.settings');
  }

}
