<?php

namespace Drupal\sb_assignment;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Datetime\DateFormatterInterface;
use Drupal\Component\Datetime\TimeInterface;

/**
 * SB Timezone Service.
 */
class TimezoneService {

  /**
   * The sb_timezone.settings config object.
   *
   * @var \Drupal\Core\Config\Config
   */
  protected $config;

  /**
   * The date formatter.
   *
   * @var \Drupal\Core\Datetime\DateFormatterInterface
   */
  protected $dateFormatter;

  /**
   * The time service.
   *
   * @var \Drupal\Component\Datetime\TimeInterface
   */
  protected $time;

  /**
   * Constructs Date/Time based on timezone settings.
   * 
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The factory for configuration objects.
   * @param \Drupal\Core\Datetime\DateFormatterInterface $date_formatter
   *   The date formatter.
   * @param \Drupal\Component\Datetime\TimeInterface $time
   *   The time service.
   */
  public function __construct(ConfigFactoryInterface $config_factory, DateFormatterInterface $date_formatter, TimeInterface $time = NULL) {
    $this->config = $config_factory->get('sb_timezone.settings');
    $this->dateFormatter = $date_formatter;
    $this->time = $time ?: \Drupal::service('datetime.time');
  }

  /**
   * Returns Current Date and Time based on Timezone config.
   */
  public function getCurrentDateTime() {
    $timezone = $this->config->get('timezone') ?: 'UTC';
    $timestamp = $this->time->getCurrentTime();

    return $this->dateFormatter->format($timestamp, 'custom', 'jS M Y - h:i A', $timezone);
  }

}
