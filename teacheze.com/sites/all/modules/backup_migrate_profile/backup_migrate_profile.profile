<?php

/**
 * Return an array of the modules to be enabled when this profile is installed.
 *
 * @return
 *   An array of modules to enable.
 */
function backup_migrate_profile_profile_modules() {
  return array('backup_migrate');
}

/**
 * Return a description of the profile for the initial installation screen.
 *
 * @return
 *   An array with keys 'name' and 'description' describing this profile,
 *   and optional 'language' to override the language selection for
 *   language-specific profiles.
 */
function backup_migrate_profile_profile_details() {
  return array(
    'name' => 'Backup and Migrate',
    'description' => 'Restore from a backup and migrate backup file.',
  );
}

/**
 * Implementation of hook_form_alter().
 *
 * Allows the profile to alter the site-configuration form. This is
 * called through custom invocation, so $form_state is not populated.
 */
function backup_migrate_profile_form_alter(&$form, $form_state, $form_id) {
  if ($form_id == 'install_configure') {

    // These settings arn't used, so add some default values and hide the elements.
    $form['site_information']['site_name']['#value'] = 'Drupal';
    $form['site_information']['site_mail']['#value'] = 'drupal@drupal.org';
    $form['admin_account']['account']['name']['#value'] = 'Drupal';
    $form['admin_account']['account']['mail']['#value'] = 'drupal@drupal.org';
    $form['admin_account']['account']['pass']['#value'] = 'drupal';
    $form['site_information']['#access'] = FALSE;
    $form['admin_account']['#access'] = FALSE;
    $form['server_settings']['#access'] = FALSE;

    // Get the form to upload a database dump.
    module_load_include('module', 'backup_migrate', 'backup_migrate');
    if (function_exists('backup_migrate_ui_manual_restore_form')) {
      $form += backup_migrate_ui_manual_restore_form();
      unset($form['disclaimer']);
      $form['#submit'][] = 'backup_migrate_profile_form_submit';
    }
    else {
      $form['error'] = array(
        '#type' => 'markup',
        '#value' => t('<p>This installer profile requires version 2 or later of Backup and Migrate. Please install the latest version and try again.</p>'),
      );
      unset($form['submit']);
    }
  }
}

/**
 * Submit handler for the "install_configure" form.
 */
function backup_migrate_profile_form_submit($form, &$form_state) {
  // Restore the database dump and redirect to the homepage.
  module_load_include('module', 'backup_migrate', 'backup_migrate');
  backup_migrate_ui_manual_restore_form_submit($form, &$form_state);
  drupal_goto('<front>');
}
