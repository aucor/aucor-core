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
     * - create users
     * - set admin as current user
     * - run callback function
     * - check the that the returning boolean is correct
     * - set subscriber as the current user
     * - run callback function
     * - check the that the returning boolean is correct
     */

    $user_admin = $this->factory->user->create(array('role' => 'administrator'));
    wp_set_current_user($user_admin);

    $this->assertTrue(
      $class->aucor_core_hide_acf_from_nonadmins(true)
    );

    $user_sub = $this->factory->user->create();
    wp_set_current_user($user_sub);

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
     * - the sub feature uses the WP __return_true or _false functions in GF filters,
     * so nothing really to test
     */

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
     * - mock args
     * - run callback function
     * - check that the return value is correct
     */
    $args = 'publish_pages';

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
     * - mock args
     * - run callback function
     * - check if the node is present (should not be)
     */
    global $wp_admin_bar;

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

    $class->aucor_core_yoast_admin_bar_render();

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
     * --first part:
     * - mock actions
     * - run callback function
     * - check that actions are removed
     * -- second part:
     * - mock args
     * - run callback function
     * - check that the retirn value is correct
     * -- third part:
     * - mock args
     * - run callback function
     * - check that the retirn value is correct
     */
    add_action('admin_notices', array(Yoast_Notification_Center::get(), 'display_notifications'));
    add_action('all_admin_notices', array(Yoast_Notification_Center::get(), 'display_notifications'));

    $class->aucor_core_remove_wpseo_notifications();

    $this->assertFalse(
      has_action('admin_notices', array(Yoast_Notification_Center::get(), 'display_notifications'))
    );
    $this->assertFalse(
      has_action('all_admin_notices', array(Yoast_Notification_Center::get(), 'display_notifications'))
    );

    $priority = 'high';

    $this->assertSame(
      'low', $class->aucor_core_seo_metabox_prio($priority)
    );

    $size = 'small';

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
