<?php
/**
 * Class DashboardTest
 *
 * @package Aucor_Core
 */

class DashboardTest extends WP_UnitTestCase {

  private $dash;

  public function setUp() {
    parent::setUp();
    $this->dash = new Aucor_Core_Dashboard;
  }

  public function tearDown() {
    unset($this->dash);
    parent::tearDown();
  }

  // test dashboard feature

  public function test_dashboard() {
    $class = $this->dash;
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

  // test dashboard sub features

  public function test_dashboard_cleanup() {
    $class = $this->dash->get_sub_features()['aucor_core_dashboard_cleanup'];
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
     * - mock args (meta boxes to be removed)
     * - check if boxes are present (should be)
     * - run callback function
     * - check if boxes' callbacks are present (should not be)
     * - nested for loops are not ideal, but with such a limited amount of inputs still manageable
     */
    global $wp_meta_boxes;

    $args = array(
      array('dashboard_right_now', 'normal'),
      array('dashboard_recent_comments', 'normal'),
      array('dashboard_incoming_links', 'normal'),
      array('dashboard_activity', 'normal'),
      array('dashboard_plugins', 'normal'),
      array('wpseo-dashboard-overview', 'normal'),
      array('dashboard_quick_press', 'side'),
      array('dashboard_recent_drafts', 'side'),
      array('dashboard_primary', 'side'),
      array('dashboard_secondary', 'side')
    );

    foreach ($args as $arg) {
      add_meta_box(
          $arg[0],
          'Test',
          'test_callback',
          'dashboard',
          $arg[1]
      );
    }

    $this->assertNotEmpty(
      $wp_meta_boxes
    );

    $class->aucor_core_admin_dashboard();

    // first context
    $normal_empty = true;
    foreach ($wp_meta_boxes['dashboard']['normal'] as $priority => $list) {
      foreach ($list as $key => $value) {
        if (!empty($value)) {
          $normal_empty = false;
        }
      }
    }
    $this->assertTrue(
      $normal_empty, 'Normal context not empty'
    );

    // second context
    $side_empty = true;
    foreach ($wp_meta_boxes['dashboard']['side'] as $priority => $list) {
      foreach ($list as $key => $value) {
        if (!empty($value)) {
          $side_empty = false;
        }
      }
    }
    $this->assertTrue(
      $side_empty, 'Side context not empty'
    );
  }

  public function test_dashboard_recent_widget() {
    $class = $this->dash->get_sub_features()['aucor_core_dashboard_recent_widget'];
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
     * - check that the meta box is present by key
     * -- second part:
     * - mock args (users, posts, revisions)
     * - buffer output
     * - run callback function
     * - check posts' visibility with high capabilities by searching for keywords
     * - add (and indirectly test) filter to lower viewing capabilities
     * - check posts' visibility with lower capabilities by searching keywords
     * -- third part:
     * - mock args
     * - check that helper function returns correct values
     * -- fourth part:
     * - check that the styles are included in the right views
     */
    global $wp_meta_boxes;

    $class->register_aucor_recent_dashboard_widget();

    $this->assertArrayHasKey(
      'aucor_recent_dashboard_widget', $wp_meta_boxes['dashboard']['side']['high']
    );

    $user1 = $this->factory->user->create(array('role' => 'editor'));
    wp_set_current_user($user1);
    $post1 = $this->factory->post->create(array('post_author' => $user1, 'post_title' => 'Test 1'));
    $post1_2 = $this->factory->post->create(array('post_author' => $user1, 'post_title' => 'Test 1_2'));
    $post1_3 = $this->factory->post->create(array('post_author' => $user1, 'post_title' => 'Test 1_3'));

    $user2 = $this->factory->user->create();
    $post2 = $this->factory->post->create(array('post_author' => $user2, 'post_title' => 'Test 2'));
    $post2_1 = $this->factory->post->create(array('post_author' => $user2, 'post_title' => 'Test 2_1'));

    // a revisions must be done with wp_insert_post so that the required additional arguments can be given
    $post1_2_rev = wp_insert_post(array(
      'post_title'  => 'Test 1_2 Rev',
      'post_status' => 'inherit',
      'post_type'   => 'revision',
      'post_parent' => 5, // Test 1_2
      'post_author' => $user1, // revision by current user on own post
    ));
    $post1_3_rev = wp_insert_post(array(
      'post_title'  => 'Test 1_3 Rev',
      'post_status' => 'inherit',
      'post_type'   => 'revision',
      'post_parent' => 6, // Test 1_3
      'post_author' => $user2, // revision on the current users post, but by another author
    ));
    $post2_rev = wp_insert_post(array(
      'post_title'  => 'Test 2_1 Rev',
      'post_status' => 'inherit',
      'post_type'   => 'revision',
      'post_parent' => 8, // Test 2_1
      'post_author' => $user1, // revision by current user, but on another author's post
    ));

    ob_start();
    $class->aucor_recent_dashboard_widget_display();
    $high_capabilities = ob_get_contents();
    ob_clean();

    // should see own posts
    $this->assertStringContainsString(
      'Test 1 (Post)', $high_capabilities
    );
    $this->assertStringContainsString(
      'Test 1_2 (Post)', $high_capabilities
    );
    $this->assertStringContainsString(
      'Test 1_3 (Post)', $high_capabilities
    );
    // should see others posts that has the users revision
    $this->assertStringContainsString(
      'Test 2_1 (Post)', $high_capabilities
    );
    // should see others posts
    $this->assertStringContainsString(
      'Test 2 (Post)', $high_capabilities
    );

    // lower user capabilities
    add_filter('aucor_core_recent_widget_user_blacklist', function ($array) {
      array_push($array, 'editor');
      return $array;
    });

    $class->aucor_recent_dashboard_widget_display();
    $lowered_capabilities = ob_get_clean();

    // should (still) see own post
    $this->assertStringContainsString(
      'Test 1 (Post)', $lowered_capabilities
    );
    $this->assertStringContainsString(
      'Test 1_2 (Post)', $lowered_capabilities
    );
    $this->assertStringContainsString(
      'Test 1_3 (Post)', $lowered_capabilities
    );
    // should (still) see post of own draft
    $this->assertStringContainsString(
      'Test 2_1 (Post)', $lowered_capabilities
    );

    // should not see others posts
    $this->assertStringNotContainsString(
      'Test 2 (Post)', $lowered_capabilities
    );

    // create an "old" post
    // must be done with wp_insert_post so that the post_modified argument can be modified
    $post3 = wp_insert_post(array(
      'post_title' => 'Test 3',
      'post_status' => 'publish',
      'post_date' =>'2010-01-01 11:11:11',
      'post_modified' =>'2010-01-01 11:11:11',
    ));

    // the helper function is used by usort and should return a value >= 0 if the first date is newer
    $this->assertGreaterThanOrEqual(
      0, $class->aucor_core_order_posts_array_by_modified_date(get_post($post1), get_post($post3))
    );

    global $wp_styles;

    // should not be (even) created
    $class->aucor_recent_dashboard_widget_styles('test.php');
    $this->assertIsNotObject(
      $wp_styles
    );
    // should be queued
    $class->aucor_recent_dashboard_widget_styles('index.php');
    $this->assertSame(
      'aucor_core-dashboard-widget-styles', $wp_styles->queue[0]
    );
  }

  public function test_dashboard_remove_panels() {
    $class = $this->dash->get_sub_features()['aucor_core_dashboard_remove_panels'];
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
     * - check that the actions don't exist
     */
    $this->assertFalse(
      has_action('try_gutenberg_panel', 'wp_try_gutenberg_panel')
    );
    $this->assertFalse(
      has_action('welcome_panel', 'wp_welcome_panel')
    );
  }

}