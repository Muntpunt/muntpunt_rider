<?php

require_once 'muntpunt_rider.civix.php';

use CRM_MuntpuntRider_ExtensionUtil as E;

/**
 * Implements hook_civicrm_config().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_config/
 */
function muntpunt_rider_civicrm_config(&$config): void {
  _muntpunt_rider_civix_civicrm_config($config);
}

/**
 * Implements hook_civicrm_install().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_install
 */
function muntpunt_rider_civicrm_install(): void {
  _muntpunt_rider_civix_civicrm_install();
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_enable
 */
function muntpunt_rider_civicrm_enable(): void {
  _muntpunt_rider_civix_civicrm_enable();
}
