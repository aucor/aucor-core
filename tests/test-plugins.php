<?php
/**
 * Class PluginsTest
 *
 * @package Aucor_Core
 */

class PluginsTest extends WP_UnitTestCase {

  private $plugins;

  public function setUp() {
    parent::setUp();
    $this->plugins = new Aucor_Core_Plugins;
  }

  public function tearDown() {
    unset($this->plugins);
    parent::tearDown();
  }

  // test plugins feature

  public function test_plugins() {
    $class = $this->plugins;
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

  // test plugins sub features

  public function test_plugins_acf() {
    $class = $this->plugins->get_sub_features()['aucor_core_plugins_acf'];
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

    // create an admin user and set it as the current user
    $user_admin = $this->factory->user->create(array('role' => 'administrator'));
    wp_set_current_user($user_admin);

    // check the that the returning boolean is correct
    $this->assertTrue(
      $class->aucor_core_hide_acf_from_nonadmins(true)
    );

    // create a subscribe user and set it as the current user
    $user_sub = $this->factory->user->create(array('role' => 'subscriber'));
    wp_set_current_user($user_sub);

    // check the that the returning boolean is correct
    $this->assertFalse(
      $class->aucor_core_hide_acf_from_nonadmins(true)
    );
  }

  public function test_plugins_gravityforms() {
    $class = $this->plugins->get_sub_features()['aucor_core_plugins_gravityforms'];
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

    // check that the filters have the __return function hooked to them
    $this->assertSame(
      10, has_filter('gform_tabindex', '__return_false')
    );
    $this->assertSame(
      10, has_filter('gform_init_scripts_footer', '__return_true')
    );
  }

  public function test_plugins_redirection() {
    $class = $this->plugins->get_sub_features()['aucor_core_plugins_redirection'];
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

    // mock args
    $args = 'publish_pages';

    // check that the return value is correct
    $this->assertSame(
      $args, $class->aucor_core_redirection_role()
    );
  }

  public function test_plugins_seo() {
    $class = $this->plugins->get_sub_features()['aucor_core_plugins_seo'];
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

    global $wp_admin_bar;

    // mock empty WP_Admin_Bar
    $wp_admin_bar = new WP_Admin_Bar;
    $wp_admin_bar->add_node(array(
        'id'    => 'wpseo-menu',
      )
    );

    // add extra item so the admin bar isn't empty when checking after removal
    $wp_admin_bar->add_node(array(
        'id'    => 'test',
      )
    );

    // run callback function
    $class->aucor_core_yoast_admin_bar_render();

    // check that the node has been removed
    $this->assertArrayNotHasKey(
      'wpseo-menu', $wp_admin_bar->get_nodes()
    );
  }

  public function test_plugins_yoast() {
    $class = $this->plugins->get_sub_features()['aucor_core_plugins_yoast'];
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

    // AUCOR_CORE_REMOVE_WPSEO_NOTIFICATIONS()

    // mock actions
    add_action('admin_notices', array(Yoast_Notification_Center::get(), 'display_notifications'));
    add_action('all_admin_notices', array(Yoast_Notification_Center::get(), 'display_notifications'));

    // run callback function
    $class->aucor_core_remove_wpseo_notifications();

    // check that actions are removed
    $this->assertFalse(
      has_action('admin_notices', array(Yoast_Notification_Center::get(), 'display_notifications'))
    );
    $this->assertFalse(
      has_action('all_admin_notices', array(Yoast_Notification_Center::get(), 'display_notifications'))
    );

    // AUCOR_CORE_SEO_METABOX_PRIO()

    // mock args
    $priority = 'high';

    // check that the return value is correct
    $this->assertSame(
      'low', $class->aucor_core_seo_metabox_prio($priority)
    );

    // AUCOR_CORE_FILTER_WPSEO_OPENGRAPH_IMAGE_SIZE()

    // mock args
    $size = 'small';

    // check that the return value is correct
    $this->assertSame(
      'large', $class->aucor_core_filter_wpseo_opengraph_image_size($size)
    );
  }

}

// test double required in the test_plugins_yoast function
class Yoast_Notification_Center {

  private static $instance = null;

  public static function get() {
    if ( null === self::$instance ) {
      self::$instance = new self();
		}

    return self::$instance;
  }
}
