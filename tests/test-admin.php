<?php
/**
 * Class AdminTest
 *
 * @package Aucor_Core
 */

class AdminTest extends WP_UnitTestCase {

  private $admin;

  public function setUp() {
    parent::setUp();
    $this->admin = new Aucor_Core_Admin;
  }

  public function tearDown() {
    unset($this->admin);
    parent::tearDown();
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
     * - create an admin user and set it as the current user
     * - mock args (menu and submenu pages)
     * - run the callback function
     * - check if the subpages are present by title (should be)
     * - create a user with insufficient capabilities and set it as the current user
     * - run the callback function
     * - check if the subpages are present (should not be)
     */
    global $menu, $submenu;

    $user_admin = $this->factory->user->create(array('role' => 'administrator'));
    wp_set_current_user($user_admin);

    add_menu_page('Appearance', 'Appearance', 'switch_themes', 'themes.php');
    add_submenu_page('themes.php', 'Themes', 'Themes', 'switch_themes', 'themes.php');
    add_submenu_page('themes.php', 'Customize', 'Customize', 'customize', 'customize.php');

    // $this->expectOutputString('foo');
    // print_r($submenu);

    $class->aucor_core_cleanup_admin_menu();

    $this->assertTrue(
      in_array('Themes', $submenu['themes.php'][0])
    );

    $this->assertTrue(
      in_array('Customize', $submenu['themes.php'][1])
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
     * Run
     * - create an admin user and set it as the current user
     * - mock args (the callback function of the action we want to remove)
     * - run the callback function
     * - check if the callback is present by comparing to mock values (should be)
     * - create a user with insufficient capabilities and set it as the current user
     * - run the callback function
     * - check if the callback is present by it's key (should not be)
     */
    global $wp_filter;

    $user_admin = $this->factory->user->create(array('role' => 'administrator'));
    wp_set_current_user($user_admin);

    $args = array(
      'update_nag' => array(
        'function'      => 'update_nag',
        'accepted_args' => 1
      )
    );

    $class->aucor_core_remove_update_nags_for_non_admins();

    $this->assertSame(
      $args, $wp_filter['admin_notices']->callbacks[3]
    );

    $user_sub = $this->factory->user->create(array('role' => 'subscriber'));
    wp_set_current_user($user_sub);

    $class->aucor_core_remove_update_nags_for_non_admins();

    $this->assertArrayNotHasKey(
      3, $wp_filter['admin_notices']->callbacks
    );
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
     * Run
     * -- first part:
     * - check that the actions have been removed
     * (the removing happens right after initialization insead of i a hook)
     * -- second part:
     * - mock args
     * - check that the callback functions return correct values
     */
    global $wp_filter;

    $this->assertArrayNotHasKey(
      'admin_color_scheme_picker', $wp_filter
    );

    $args = array(
      'aim'        => '',
      'jabber'     => '',
      'yim'        => '',
      'googleplus' => '',
      'twitter'    => '',
      'facebook'   => ''
    );
    $this->assertSame(
      array(), $class->aucor_core_remove_contact_methods($args)
    );
  }

  public function test_admin_remove_customizer() {
    // needed to mock the admin bar
    require_once ABSPATH . WPINC . '/class-wp-admin-bar.php';

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
     * Run
     * - mock args (admin bar)
     * - run callback function
     * - check if the node is present by it's key (should not be)
     */
    $args = new WP_Admin_Bar;
    $args->add_node(array(
        'id' => 'customize'
      )
    );
    $args->add_node(array(
        'id' => 'test'
      )
    );

    $class->aucor_core_remove_customizer_admin_bar($args);

    $this->assertArrayNotHasKey(
      'customize', $args->get_nodes()
    );
  }

}
