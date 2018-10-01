<?php
/**
 * Class Admin_Menu_Cleanup
 */
class Aucor_Core_Admin_Menu_Cleanup extends Aucor_Core_Sub_Feature {

  public function setup() {

    // var: key
    $this->set('key', 'aucor_core_admin_menu_cleanup');

    // var: name
    $this->set('name', 'Clean up admin menus for non-admins');

    // var: is_active
    $this->set('is_active', true);

  }

  /**
   * Run feature
   */
  public function run() {
    add_action('admin_menu', array('Aucor_Core_Admin_Menu_Cleanup', 'aucor_core_cleanup_admin_menu'), 9999);
  }

  /**
   * Clean up admin menus for non-admins
   */
  public static function aucor_core_cleanup_admin_menu() {
    if (!current_user_can('administrator')) {
      remove_submenu_page('themes.php', 'themes.php');
      remove_submenu_page('themes.php', 'customize.php');
    }
  }

}
