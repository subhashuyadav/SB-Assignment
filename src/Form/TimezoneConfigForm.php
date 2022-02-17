<?php

namespace Drupal\sb_assignment\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Config\ConfigFactoryInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Timezone Configuration Form.
 */
class TimezoneConfigForm extends ConfigFormBase {

  /**
   * The form settings.
   */
  protected $settings;

  /**
   * {@inheritdoc}
   */
  public function __construct(ConfigFactoryInterface $config_factory) {
    $this->settings = 'sb_timezone.settings';

    parent::__construct($config_factory);
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('config.factory')
    );
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['sb_timezone'];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'sb_timezone_config_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    // Get default values.
    $config = $this->config($this->settings);

    $form['country'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Country'),
      '#default_value' => $config->get('country'),
    ];

    $form['city'] = [
      '#title' => $this->t('City'),
      '#type' => 'textfield',
      '#default_value' => $config->get('city'),
    ];

    $timezone_list = $this->getTimezones();
    $form['timezone'] = [
      '#title' => $this->t('Timezone'),
      '#type' => 'select',
      '#options' => ['' => '- Select Timezone -'] + $timezone_list,
      '#default_value' => $config->get('timezone'),
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * Returns the timezone list,
   */
  public function getTimezones() {
    return [
      'America/Chicago' => 'America/Chicago' ,
      'America/New_York' => 'America/New_York',
      'Asia/Tokyo' => 'Asia/Tokyo',
      'Asia/Dubai' => 'Asia/Dubai',
      'Asia/Kolkata' => 'Asia/Kolkata',
      'Europe/Amsterdam' => 'Europe/Amsterdam',
      'Europe/Oslo' => 'Europe/Oslo',
      'Europe/London' => 'Europe/London',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);

    // Save the configuration.
    $config = $this->configFactory->getEditable($this->settings);
    
    $config->set('country', $form_state->getValue('country'));
    $config->set('city', $form_state->getValue('city'));
    $config->set('timezone', $form_state->getValue('timezone'));
    $config->save();

  }

}
