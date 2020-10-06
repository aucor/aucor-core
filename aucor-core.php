<?php
/**
 * Plugin Name:    Aucor Core (Backwards Compatibility, Do Not Delete)
 * Description:    Auto-migrates old plugin naming to new – do not activate or delete
 * Version:        1.1.4
 * Author:         Aucor Oy
 * Author URI:     https://www.aucor.fi
 * Text Domain:    aucor-core
 */

/**
 * Main file used to be plugin.php but it was renamed
 * to this file and that was a mistake as WP saves
 * plugin file path to DB and causes errors on updating.
 *
 * To fix this mess, we'll need to keep this auto-migration
 * for some sites that started using this plugin during the
 * unlucky renaming phase to autofix it and remove it maybe
 * for version 2.0.0.
 */

// include all plguin functionality
require_once 'plugin.php';

/**
 * Auto-migrate active plugin data in DB to stop
 * using "aucor-core.php" and prefer "plugin.php"
 */
$active_plugins = is_multisite() ? get_site_option('active_plugins') : get_option('active_plugins');
$active_plugins_changed = false;

if (is_array($active_plugins) && !empty($active_plugins)) {
  foreach ($active_plugins as $i => $name) {
    if ($name == 'aucor-core/aucor-core.php') {
      $active_plugins[$i] = 'aucor-core/plugin.php';
      $active_plugins_changed = true;
    }
  }
  if ($active_plugins_changed) {
    // remove duplicate plugin activations
    $active_plugins = array_unique($active_plugins);
    $status = is_multisite() ? update_site_option('active_plugins', $active_plugins) : update_option('active_plugins', $active_plugins);
  }
}
