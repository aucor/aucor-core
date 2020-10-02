<?php
/**
 * Plugin Name:    Aucor Core
 * Description:    Core functionality to Aucor Starter powered sites
 * Version:        1.1.3
 * Author:         Aucor Oy
 * Author URI:     https://www.aucor.fi
 * Text Domain:    aucor-core
 */

/**
 * Main file used to be plugin.php but it was renamed
 * and WP has issue with this because path to plugin
 * is saved to DB. Also, after adding this file WP
 * seems to prefer this as the plugin main file instead
 * of aucor-core.php. This file is forever reminder for
 * not to mess with plugin's main file name.
 */
require_once 'aucor-core.php';
