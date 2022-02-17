<?php

namespace Drupal\sb_assignment\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\sb_assignment\TimezoneService;
use Drupal\Core\Cache\Cache;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides an 'Site Location' block with timezone based current time and location.
 *
 * @Block(
 *   id = "site_location_block",
 *   admin_label = @Translation("Site Location"),
 *   category = @Translation("Custom")
 * )
 */
class SiteLocationBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * The sb timezone service.
   *
   * @var \Drupal\sb_assignment\TimezoneService
   */
  protected $sbTimezone;

  /**
   * The sb_timezone.settings config object.
   *
   * @var \Drupal\Core\Config\Config
   */
  protected $config;

  /**
   * Constructs an SiteLocationBlock object.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\sb_assignment\TimezoneService
   *   The Timezone service.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, TimezoneService $sb_timezone, ConfigFactoryInterface $config_factory) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->sbTimezone = $sb_timezone;
    $this->config = $config_factory->get('sb_timezone.settings');
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('sb_assignment.timezone'),
      $container->get('config.factory')
    );
  }

  /**
   * Returns Site Location based on Timezone config.
   */
  public function getLocation() {
    $location = [];
    if (!empty($city = $this->config->get('city'))) {
      $location['city'] = $city;
    }
    if (!empty($country = $this->config->get('country'))) {
      $location['country'] = $country;
    }

    return $location;
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $current_time = $this->sbTimezone->getCurrentDateTime();
    $location = $this->getLocation();

    return [
      '#theme' => 'site_location_block',
      '#data' => array_merge(['time' => $current_time], $location),
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheTags() {
    $cache_tags = Cache::mergeTags(parent::getCacheTags(), ['config:sb_assignment.timezone']);
    return $cache_tags;
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheMaxAge() {
    return 60;
  }

}
