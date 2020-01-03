<?php
/**
 * Class AdminTest
 *
 * @package Aucor_Core
 */

class AdminTest extends WP_UnitTestCase {

  public function setUp() {
    parent::setUp();
    $this->admin = new Aucor_Core_Admin;
  }

  // test admin feature

  public function test_admin() {
    $class = $this->admin;
    // key
    $this->assertNotEmpty(
      $class->get_key()
    );
    // name
    $this->assertNotEmpty(
      $class->get_name()
    );
    // status
    $this->assertTrue(
      $class->is_active()
    );

    // sub feature init
    $this->assertNotEmpty(
      $class->get_sub_features()
    );
  }

  // test admin sub features

	public function test_admin_gallery() {
    $class = $this->admin->get_sub_features()['aucor_core_admin_gallery'];
    // key
    $this->assertNotEmpty(
       $class->get_key()
    );
    // name
    $this->assertNotEmpty(
      $class->get_name()
    );
    // status
    $this->assertTrue(
      $class->is_active()
    );

    /**
     * Run
     * - mock correct args
     * - check that the callback function returns those args
     */
    $args = array(
      'galleryDefaults' => array(
        'link'    => 'file',
        'size'    => 'medium',
        'columns' => '2',
      )
    );
    $this->assertEquals(
      $args, $class->aucor_core_gallery_defaults(array())
    );
  }

  public function test_admin_image_link() {
    $class = $this->admin->get_sub_features()['aucor_core_admin_image_link'];
    // key
    $this->assertNotEmpty(
      $class->get_key()
    );
    // name
    $this->assertNotEmpty(
      $class->get_name()
    );
    // status
    $this->assertTrue(
      $class->is_active()
    );

    /**
     * Run
     * - inject a wrong value to options
     * - run callback function
     * - get the option and check that it's correct
     */
    update_option('image_default_link_type', 'file');
    $class->aucor_core_default_image_link_to_none();
    $this->assertEquals(
      'none', get_option('image_default_link_type')
    );
  }

  public function test_admin_login() {
    $class = $this->admin->get_sub_features()['aucor_core_admin_login'];
    // key
    $this->assertNotEmpty(
      $class->get_key()
    );
    // name
    $this->assertNotEmpty(
      $class->get_name()
    );
    // status
    $this->assertTrue(
      $class->is_active()
    );

    /**
     * Run
     * - check that the callback functions return correct values
     */
    $this->assertEquals(
      get_bloginfo('name'), $class->aucor_core_login_logo_url_title('Test')
    );
    $this->assertEquals(
      get_site_url(), $class->aucor_core_login_logo_url('https://test.test')
    );
  }

  public function test_admin_menu_cleanup() {
    $class = $this->admin->get_sub_features()['aucor_core_admin_menu_cleanup'];
    // key
    $this->assertNotEmpty(
      $class->get_key()
    );
    // name
    $this->assertNotEmpty(
      $class->get_name()
    );
    // status
    $this->assertTrue(
      $class->is_active()
    );

    /**
     * Run
     * - create a admin user and set it as the current user
     * - mock menu and submenu pages
     * - run the callback function
     * - check that the subpages are still present
     * - create a user with insufficient capabilities and set it as the current user
     * - run the callback function
     * - check that subpages are removed
     */
    global $menu, $submenu;

    $user_admin = $this->factory->user->create(array('role' => 'administrator'));
    wp_set_current_user($user_admin);

    add_menu_page('Appearance', 'Appearance', 'switch_themes', 'themes.php');
    add_submenu_page('themes.php', 'Themes', 'Themes', 'switch_themes', 'themes.php');
    add_submenu_page('themes.php', 'Customize', 'Customize', 'customize', 'customize.php');

    $class->aucor_core_cleanup_admin_menu();

    $this->assertSame(
      'themes.php', $submenu['themes.php'][0][2]
    );
    $this->assertSame(
      'customize.php', $submenu['themes.php'][1][2]
    );

    $user_sub = $this->factory->user->create(array('role' => 'subscriber'));
    wp_set_current_user($user_sub);

    $class->aucor_core_cleanup_admin_menu();

    $this->assertSame(
      array(), $submenu['themes.php']
    );
  }

  public function test_admin_notifications() {
    $class = $this->admin->get_sub_features()['aucor_core_admin_notifications'];
    // key
    $this->assertNotEmpty(
      $class->get_key()
    );
    // name
    $this->assertNotEmpty(
      $class->get_name()
    );
    // status
    $this->assertTrue(
      $class->is_active()
    );

    /**
     * Run -
     */

  }

  public function test_admin_profile_cleanup() {
    $class = $this->admin->get_sub_features()['aucor_core_admin_profile_cleanup'];
    // key
    $this->assertNotEmpty(
      $class->get_key()
    );
    // name
    $this->assertNotEmpty(
      $class->get_name()
    );
    // status
    $this->assertTrue(
      $class->is_active()
    );

    /**
     * Run -
     */

  }

  public function test_admin_remove_customizer() {
    $class = $this->admin->get_sub_features()['aucor_core_admin_remove_customizer'];
    // key
    $this->assertNotEmpty(
      $class->get_key()
    );
    // name
    $this->assertNotEmpty(
      $class->get_name()
    );
    // status
    $this->assertTrue(
      $class->is_active()
    );

    /**
     * Run -
     */

  }

}
