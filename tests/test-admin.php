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
     */

    // check filter hook
    $this->assertSame(
      10, has_filter('media_view_settings', array($class, 'aucor_core_gallery_defaults'))
    );

    // AUCOR_CORE_GALLERY_DEFAULTS()

    // mock correct args
    $args = array(
      'galleryDefaults' => array(
        'link'    => 'file',
        'size'    => 'medium',
        'columns' => '2',
      )
    );
    // check that the callback function returns those args
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
     */
    // check action hook
    $this->assertSame(
      10, has_action('admin_init', array($class, 'aucor_core_default_image_link_to_none'))
    );

    // AUCOR_CORE_DEFAULT_IMAGE_LINK_TO_NONE()

    // inject a wrong value to options
    update_option('image_default_link_type', 'file');

    //run callback function
    $class->aucor_core_default_image_link_to_none();

    // check that the option is correct
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
     */

    // check filter hooks
    $this->assertSame(
      10, has_filter('login_headertext', array($class, 'aucor_core_login_logo_url_title'))
    );
    $this->assertSame(
      10, has_filter('login_headerurl', array($class, 'aucor_core_login_logo_url'))
    );

    // AUCOR_CORE_LOGIN_LOGO_URL_TITLE()

    // check that the callback function returns correct value
    $this->assertEquals(
      get_bloginfo('name'), $class->aucor_core_login_logo_url_title('Test')
    );

    // AUCOR_CORE_LOGIN_LOGO_URL()

    // check that the callback function returns correct value
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
     */

    // check action hook
    $this->assertSame(
      9999, has_action('admin_menu', array($class, 'aucor_core_cleanup_admin_menu'))
    );

    // AUCOR_CORE_CLEANUP_ADMIN_MENU()

    global $menu, $submenu;

    // create an admin user and set it as the current user
    $user_admin = $this->factory->user->create(array('role' => 'administrator'));
    wp_set_current_user($user_admin);

    // mock menu and submenu pages
    add_menu_page('Appearance', 'Appearance', 'switch_themes', 'themes.php');
    add_submenu_page('themes.php', 'Themes', 'Themes', 'switch_themes', 'themes.php');
    add_submenu_page('themes.php', 'Customize', 'Customize', 'customize', 'customize.php');

    // run the callback function
    $class->aucor_core_cleanup_admin_menu();

    // check that the subpages are present
    $this->assertTrue(
      in_array('Themes', $submenu['themes.php'][0])
    );

    $this->assertTrue(
      in_array('Customize', $submenu['themes.php'][1])
    );

    // create a user with insufficient capabilities and set it as the current user
    $user_sub = $this->factory->user->create(array('role' => 'subscriber'));
    wp_set_current_user($user_sub);

    // run the callback function
    $class->aucor_core_cleanup_admin_menu();

    // check that the subpages have been removed
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
     */

    // check action hook
    $this->assertSame(
      1, has_action('admin_head', array($class, 'aucor_core_remove_update_nags_for_non_admins'))
    );

    // AUCOR_CORE_REMOVE_UPDATE_NAGS_FOR_NON_ADMINS()

     // create an admin user and set it as the current user
    $user_admin = $this->factory->user->create(array('role' => 'administrator'));
    wp_set_current_user($user_admin);

    // run the callback function
    $class->aucor_core_remove_update_nags_for_non_admins();

    // check that the callback is present by comparing to the callbacks priority
    $this->assertEquals(
      3, has_action('admin_notices', 'update_nag')
    );

    // create a user with insufficient capabilities and set it as the current user
    $user_sub = $this->factory->user->create(array('role' => 'subscriber'));
    wp_set_current_user($user_sub);

    // run the callback function
    $class->aucor_core_remove_update_nags_for_non_admins();

    // check that the callback has been removed
    $this->assertFalse(
      has_action('admin_notices', 'update_nag')
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
     */

    global $wp_filter;

    // check that the actions have been removed
    $this->assertArrayNotHasKey(
      'admin_color_scheme_picker', $wp_filter
    );

    // check filter hook
    $this->assertSame(
      10, has_filter( 'user_contactmethods', array($class, 'aucor_core_remove_contact_methods'))
    );

    // AUCOR_CORE_REMOVE_CONTACT_METHODS()

    // mock args
    $args = array(
      'aim'        => '',
      'jabber'     => '',
      'yim'        => '',
      'googleplus' => '',
      'twitter'    => '',
      'facebook'   => ''
    );

    // check that the callback functions return correct values
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
     */

    // check action hook
    $this->assertSame(
      999, has_action('admin_bar_menu', array($class, 'aucor_core_remove_customizer_admin_bar'))
    );

    // AUCOR_CORE_REMOVE_CUSTOMIZER_ADMIN_BAR()

    // mock admin bar
    $args = new WP_Admin_Bar;
    $args->add_node(array(
        'id' => 'customize'
      )
    );
    // add extra item so the admin bar isn't empty when checking after removal
    $args->add_node(array(
        'id' => 'test'
      )
    );

    // run callback function
    $class->aucor_core_remove_customizer_admin_bar($args);

    // check that the node has been removed
    $this->assertArrayNotHasKey(
      'customize', $args->get_nodes()
    );
  }

}
