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
     */

    // check filter hook
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
     */

    // check defined constant
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
     */

    // check defined constant
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
     */

    // check filter hooks
    $this->assertSame(
      10, has_filter('the_generator', '__return_empty_string')
    );
    $this->assertSame(
      10, has_filter('xmlrpc_enabled', '__return_false')
    );
    $this->assertSame(
      10, has_filter('xmlrpc_enabled', '__return_false')
    );
    $this->assertSame(
      10, has_filter('wp_headers', array($class, 'aucor_core_remove_pingback_header'))
    );
    $this->assertSame(
      10, has_filter('xmlrpc_methods', array($class, 'aucor_core_remove_pingback_functionality'))
    );

    // AUCOR_CORE_REMOVE_PINGBACK_HEADER()

    // mock args
    // include a second item so array isn't empty on deletion
    $headers = array('X-Pingback' => '', 'Test'  => '');

    // check that the callback function has removed the correct value
    $this->assertArrayNotHasKey(
      'X-Pingback', $class->aucor_core_remove_pingback_header($headers)
    );

    // AUCOR_CORE_REMOVE_PINGBACK_FUNCTIONALITY()

    // mock args
    // include a second item so array isn't empty on deletion
    $methods = array('pingback.ping' => '', 'Test' => '');

    // check that the callback function has removed the correct value
    $this->assertArrayNotHasKey(
      'pingback.ping', $class->aucor_core_remove_pingback_functionality($methods)
    );

    // check that the hooks have been removed
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
     */

    // check filter hooks
    $this->assertSame(
      100, has_filter('the_author', array($class, 'aucor_core_rename_authors'))
    );
    $this->assertSame(
      100, has_filter('the_modified_author', array($class, 'aucor_core_rename_authors'))
    );
    $this->assertSame(
      100, has_filter('get_the_author_link', array($class, 'aucor_core_author_link_to_front_page'))
    );
    $this->assertSame(
      1000, has_filter('rest_endpoints', array($class,'aucor_core_disable_user_endpoints'))
    );

    // AUCOR_CORE_RENAME_AUTHORS()

    // mock args
    $name = 'Test';

    // check that the callback function returns the correct value
    $this->assertSame(
      get_bloginfo('name'), $class->aucor_core_rename_authors($name)
    );

    // set current screen to admin screen
    set_current_screen('index.php');

    // check that the callback function returns the correct value
    $this->assertSame(
      'Test', $class->aucor_core_rename_authors($name)
    );

    // AUCOR_CORE_AUTHOR_LINK_TO_FRONT_PAGE()

    // mock args
    $url = 'Test';

    // check that the callback function returns the correct value
    $this->assertSame(
      get_site_url(), $class->aucor_core_author_link_to_front_page($url)
    );

    // AUCOR_CORE_DISABLE_USER_ENDPOINTS()

    // mock args
    // include a second item so that the array isn't empty when the first item is removed
    // a "random" item also makes it possible to cover the path when !is_set('/wp/v2/users') instead of using the second looked for key
    $endpoints = array('/wp/v2/users' => '', 'Test' => '');

    // check that the callback function returns the correct value
    $this->assertArrayNotHasKey(
      '/wp/v2/users', $class->aucor_core_disable_user_endpoints($endpoints)
    );

    $endpoints['/wp/v2/users/(?P<id>[\d]+)'] = '';

    // check that the callback function returns the correct value
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
     */

    // check filter hook
    $this->assertSame(
      11, has_filter('comment_moderation_recipients', array($class, 'aucor_core_comment_moderation_post_author_only'))
    );

    // AUCOR_CORE_COMMENT_MODERATION_POST_AUTHOR_ONLY()

    // mock user, post, comments, args
    $user = $this->factory->user->create(array('role' => 'subscriber', 'user_email' => 'user@user.user'));
    $post = $this->factory->post->create(array('post_author' => $user));
    $comment = $this->factory->comment->create(array('comment_post_ID' => $post));
    $emails = array('admin@admin.admin');

    // check that the callback function returns correct value
    $this->assertSame(
      array('admin@admin.admin'), $class->aucor_core_comment_moderation_post_author_only($emails, $comment)
    );

    // increase user capabilities
    get_userdata($user)->set_role('editor');

    // check that the callback function returns correct value
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
     */

    // check action and filter hooks
    $this->assertSame(
      10, has_action('admin_init', array($class, 'aucor_core_disable_comments_post_types_support'))
    );
    $this->assertSame(
      10, has_action('admin_menu', array($class, 'aucor_core_disable_comments_admin_menu'))
    );
    $this->assertSame(
      10, has_action('admin_init', array($class, 'aucor_core_disable_comments_admin_menu_redirect'))
    );
    $this->assertSame(
      10, has_action('admin_init', array($class, 'aucor_core_disable_comments_dashboard'))
    );
    $this->assertSame(
      10, has_action('wp_before_admin_bar_render', array($class, 'aucor_core_admin_bar_render'))
    );
    $this->assertSame(
      10, has_filter('comments_array', array($class, 'aucor_core_disable_comments_hide_existing_comments'))
    );
    $this->assertSame(
      20, has_filter('comments_open', '__return_false')
    );
    $this->assertSame(
      20, has_filter('pings_open', '__return_false')
    );

    // AUCOR_CORE_DISABLE_COMMENTS_POST_TYPES_SUPPORT()

    // run callback function
    $class->aucor_core_disable_comments_post_types_support();

    $post_types = get_post_types();

    // check that support has been removed from post types
    foreach ($post_types as $post_type) {
      $this->assertFalse(
        post_type_supports($post_type, 'comments'), $post_type . ' supports comments'
      );
      $this->assertFalse(
        post_type_supports($post_type, 'trackbacks'), $post_type . ' supports trackbacks'
      );
    }

    // AUCOR_CORE_DISABLE_COMMENTS_ADMIN_MENU()

    global $menu;

    // mock menu pages
    add_menu_page('Comments', 'Comments', 'edit_posts', 'edit-comments.php');

    // run callback function
    $class->aucor_core_disable_comments_admin_menu();

    // check that the menu item has been removed
    foreach ($menu as $item) {
      $this->assertNotEquals(
        'edit-comments.php', $item[2], $item[0] . ' contains edit-comments.php'
      );
    }

    // AUCOR_CORE_DISABLE_COMMENTS_ADMIN_MENU_REDIRECT()

    // this function is only partially covered, as the other branch calls exit, which makes it untestable

    global $pagenow;
    $pagenow = 'index.php';

    $class->aucor_core_disable_comments_admin_menu_redirect();

    $this->assertSame(
      'index.php', $pagenow
    );

    // $pagenow = 'edit-comments.php';
    // $class->aucor_core_disable_comments_admin_menu_redirect();

    // AUCOR_CORE_DISABLE_COMMENTS_DASHBOARD()

    global $wp_meta_boxes;

    // mock metabox
    add_meta_box(
      'dashboard_recent_comments',
      'Test',
      'test_callback',
      'dashboard',
      'normal'
    );

    // check taht the metabox is present
    $this->assertNotEmpty(
      $wp_meta_boxes['dashboard']['normal']['default']['dashboard_recent_comments']
    );

    // run callback function
    $class->aucor_core_disable_comments_dashboard();

    // check that the correct item has been removed
    $this->assertEmpty(
      $wp_meta_boxes['dashboard']['normal']['default']['dashboard_recent_comments']
    );

    // AUCOR_CORE_ADMIN_BAR_RENDER()

    global $wp_admin_bar;

    // mock admin bar
    $wp_admin_bar = new WP_Admin_Bar;
    $wp_admin_bar->add_node(array(
        'id' => 'comments'
      )
    );
    // add extra item so the admin bar isn't empty when checking after removal
    $wp_admin_bar->add_node(array(
        'id' => 'test'
      )
    );

    // run callback function
    $class->aucor_core_admin_bar_render();

    // check that the correct value has been removed
    $this->assertArrayNotHasKey(
      'comments', $wp_admin_bar->get_nodes()
    );

    // AUCOR_CORE_DISABLE_COMMENTS_HIDE_EXISTING_COMMENTS()

    // mock comments
    $comment1 = $this->factory->comment->create();
    $comment2 = $this->factory->comment->create();
    $comments =  array($comment1, $comment2);

    // check that the callback function returns correct values
    $this->assertSame(
      array(), $class->aucor_core_disable_comments_hide_existing_comments($comments)
    );
  }

}
