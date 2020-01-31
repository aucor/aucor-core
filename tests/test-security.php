<?php
/**
 * Class SecurityTest
 *
 * @package Aucor_Core
 */

class SecurityTest extends WP_UnitTestCase {

  private $security;

  public function setUp() {
    parent::setUp();
    $this->security = new Aucor_Core_Security;
  }

  public function tearDown() {
    unset($this->security);
    parent::tearDown();
  }

  // test security feature

  public function test_security() {
    $class = $this->security;
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

  // test security sub features

  public function test_security_disable_admin_email_check() {
    $class = $this->security->get_sub_features()['aucor_core_security_disable_admin_email_check'];
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
     * - check that the filter has the __return function hooked to it
     */
    $this->assertSame(
      10, has_filter('admin_email_check_interval', '__return_false')
    );
  }

  public function test_security_disable_file_edit() {
    $class = $this->security->get_sub_features()['aucor_core_security_disable_file_edit'];
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
     * - check defined constant
     */
    $this->assertTrue(
      DISALLOW_FILE_EDIT
    );
  }

  public function test_security_disable_unfiltered_html() {
    $class = $this->security->get_sub_features()['aucor_core_security_disable_unfiltered_html'];
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
     * - check defined constant
     */
    $this->assertTrue(
      DISALLOW_UNFILTERED_HTML
    );
  }

  public function test_security_head_cleanup() {
    $class = $this->security->get_sub_features()['aucor_core_security_head_cleanup'];
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
     * - check that the two first filters have the __return function hooked to them
     * -- second part:
     * - mock args
     * - run callback function
     * - check that the callback function has removed correct value
     * -- third part:
     * - mock args
     * - run callback function
     * - check that the callback function has removed correct value
     * -- fourth part:
     * - check that the actions have been removed
     */
    $this->assertSame(
      10, has_filter('the_generator', '__return_empty_string')
    );
    $this->assertSame(
      10, has_filter('xmlrpc_enabled', '__return_false')
    );

    // include a second item so array isn't empty on deletion
    $headers = array('X-Pingback' => '', 'Test'  => '');

    $this->assertArrayNotHasKey(
      'X-Pingback', $class->aucor_core_remove_pingback_header($headers)
    );

    $methods = array('pingback.ping' => '', 'Test' => '');

    $this->assertArrayNotHasKey(
      'pingback.ping', $class->aucor_core_remove_pingback_functionality($methods)
    );

    $this->assertFalse(
      has_action('wp_head', 'rsd_link')
    );
    $this->assertFalse(
      has_action('wp_head', 'feed_links')
    );
    $this->assertFalse(
      has_action('wp_head', 'index_rel_link')
    );
    $this->assertFalse(
      has_action('wp_head', 'wlwmanifest_link')
    );
    $this->assertFalse(
      has_action('wp_head', 'feed_links_extra')
    );
    $this->assertFalse(
      has_action('wp_head', 'start_post_rel_link')
    );
    $this->assertFalse(
      has_action('wp_head', 'parent_post_rel_link')
    );
    $this->assertFalse(
      has_action('wp_head', 'adjacent_posts_rel_link')
    );
    $this->assertFalse(
      has_action('wp_head', 'rest_output_link_wp_head')
    );
    $this->assertFalse(
      has_action('wp_head', 'wp_oembed_add_discovery_links')
    );
  }

  public function test_security_hide_users() {
    $class = $this->security->get_sub_features()['aucor_core_security_hide_users'];
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
     * - mock args
     * - run callback function
     * - check that the correct value is returned
     * - set current screen to admin screen
     * - run callback function
     * - check that the correct value is returned
     * -- second part:
     * - mock args
     * - check that the correct value is returned
     * -- third part:
     * - mock args
     * - check that the correct value is returned
     */
    $name = 'Test';

    $this->assertSame(
      get_bloginfo('name'), $class->aucor_core_rename_authors($name)
    );

    set_current_screen('index.php');

    $this->assertSame(
      'Test', $class->aucor_core_rename_authors($name)
    );

    $url = 'Test';

    $this->assertSame(
      get_site_url(), $class->aucor_core_author_link_to_front_page($url)
    );

    // include a second item so that the array isn't empty when the first item is removed
    // a "random" item also makes it possible to cover the path when !is_set('/wp/v2/users') instead of using the second looked for key
    $endpoints = array('/wp/v2/users' => '', 'Test' => '');

    $this->assertArrayNotHasKey(
      '/wp/v2/users', $class->aucor_core_disable_user_endpoints($endpoints)
    );

    $endpoints['/wp/v2/users/(?P<id>[\d]+)'] = '';

    $this->assertArrayNotHasKey(
      '/wp/v2/users', $class->aucor_core_disable_user_endpoints($endpoints)
    );
  }

  public function test_security_remove_comment_moderation() {
    $class = $this->security->get_sub_features()['aucor_core_security_remove_comment_moderation'];
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
     * - increase user capabilities
     * - run callback function
     * - check that the return value is correct
     */

    $user = $this->factory->user->create(array('role' => 'subscriber', 'user_email' => 'user@user.user'));
    $post = $this->factory->post->create(array('post_author' => $user));
    $comment = $this->factory->comment->create(array('comment_post_ID' => $post));
    $emails = array('admin@admin.admin');

    $this->assertSame(
      array('admin@admin.admin'), $class->aucor_core_comment_moderation_post_author_only($emails, $comment)
    );

    get_userdata($user)->set_role('editor');

    $this->assertSame(
      array('user@user.user'), $class->aucor_core_comment_moderation_post_author_only($emails, $comment)
    );
  }

  public function test_security_remove_commenting() {
    $class = $this->security->get_sub_features()['aucor_core_security_remove_commenting'];
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
     * - run callback function
     * - check that support has been removed from post types
     * -- second part:
     * - mock args
     * - run callback function
     * - check that the menu item has been removed
     * -- third part:
     * - TODO
     * -- fourth part:
     * - mock args
     * - run callback function
     * - check that the correct item has been removed
     * -- fifth part:
     * - mock args
     * - run callback function
     * - check that the return value is correct
     */
    $class->aucor_core_disable_comments_post_types_support();

    $post_types = get_post_types();

    foreach ($post_types as $post_type) {
      $this->assertFalse(
        post_type_supports($post_type, 'comments'), $post_type . ' supports comments'
      );
      $this->assertFalse(
        post_type_supports($post_type, 'trackbacks'), $post_type . ' supports trackbacks'
      );
    }

    global $menu;

    add_menu_page('Comments', 'Comments', 'edit_posts', 'edit-comments.php');

    $class->aucor_core_disable_comments_admin_menu();

    foreach ($menu as $item) {
      $this->assertNotEquals(
        'edit-comments.php', $item[2], $item[0] . ' contains edit-comments.php'
      );
    }

    // TODO, figure out what to do with the exit
    // global $pagenow;
    // $pagenow = 'edit-comments.php';
    // $class->aucor_core_disable_comments_admin_menu_redirect();

    global $wp_meta_boxes;

    add_meta_box(
      'dashboard_recent_comments',
      'Test',
      'test_callback',
      'dashboard',
      'normal'
    );

    $this->assertNotEmpty(
      $wp_meta_boxes['dashboard']['normal']['default']['dashboard_recent_comments']
    );

    $class->aucor_core_disable_comments_dashboard();

    $this->assertEmpty(
      $wp_meta_boxes['dashboard']['normal']['default']['dashboard_recent_comments']
    );

    global $wp_admin_bar;

    $wp_admin_bar->add_node(array(
        'id'    => 'comments',
      )
    );

    $class->aucor_core_admin_bar_render();

    $this->assertArrayNotHasKey(
      'comments', $wp_admin_bar->get_nodes()
    );

    $comment1 = $this->factory->comment->create();
    $comment2 = $this->factory->comment->create();
    $comments =  array($comment1, $comment2);

    $this->assertSame(
      array(), $class->aucor_core_disable_comments_hide_existing_comments($comments)
    );
  }

}
