<?php
/**
 * Class SpeedTest
 *
 * @package Aucor_Core
 */

class SpeedTest extends WP_UnitTestCase {

  private $speed;

  public function setUp() {
    parent::setUp();
    $this->speed = new Aucor_Core_Speed;
  }

  public function tearDown() {
    unset($this->speed);
    parent::tearDown();
  }

  // test speed feature

  public function test_speed() {
    $class = $this->speed;
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

  // test speed sub features

  public function test_speed_limit_revisions() {
    $class = $this->speed->get_sub_features()['aucor_core_speed_limit_revisions'];
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
    $number = 10;
    $post_id = 1;

    $this->assertSame(
      5, $class->aucor_core_limit_revision_number($number, $post_id)
    );
  }

  public function test_speed_move_jquery() {
    $class = $this->speed->get_sub_features()['aucor_core_speed_move_jquery'];
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
     * - check that data has not been added to the dependencies
     * - move out of admin view
     * - run callback function
     * - check that scripts have been moved
     */
    $scripts = new WP_Scripts();
    $scripts->add('jquery', false, array('jquery-core', 'jquery-migrate'));
    $scripts->add('jquery-core', '/jquery.js', array());
    $scripts->add('jquery-migrate', '/jquery-migrate.js', array());

    $class->aucor_core_move_jquery_into_footer($scripts);

    $this->assertFalse(
      $scripts->get_data('jquery', 'group')
    );
    $this->assertFalse(
      $scripts->get_data('jquery-core', 'group')
    );
    $this->assertFalse(
      $scripts->get_data('jquery-migrate', 'group')
    );

    $this->go_to('/'); // get out of is_admin()

    $class->aucor_core_move_jquery_into_footer($scripts);

    $this->expectOutputRegex('/^(?:<script[^>]+><\\/script>\\n){2}$/');

    $scripts->do_items('jquery', 0);
    $this->assertNotContains('jquery', $scripts->done);
    $this->assertNotContains('jquery-core', $scripts->done);
    $this->assertNotContains('jquery-migrate', $scripts->done);

    $scripts->do_items('jquery', 1);
    $this->assertContains('jquery', $scripts->done);
    $this->assertContains('jquery-core', $scripts->done);
    $this->assertContains('jquery-migrate', $scripts->done);
  }

  public function test_speed_remove_emojis() {
    $class = $this->speed->get_sub_features()['aucor_core_speed_remove_emojis'];
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
     * - run callback function
     * - check that filters and actions have been removed
     */
    $class->aucor_core_disable_emojis();

    $this->assertFalse(
      has_action('wp_head', 'print_emoji_detection_script', 7)
    );
    $this->assertFalse(
      has_action('admin_print_scripts', 'print_emoji_detection_script')
    );
    $this->assertFalse(
      has_action('wp_print_styles', 'print_emoji_styles')
    );
    $this->assertFalse(
      has_action('admin_print_styles', 'print_emoji_styles')
    );
    $this->assertFalse(
      has_filter('the_content_feed', 'wp_staticize_emoji')
    );
    $this->assertFalse(
      has_filter('comment_text_rss', 'wp_staticize_emoji')
    );
    $this->assertFalse(
      has_filter('wp_mail', 'wp_staticize_emoji_for_email')
    );
  }

  public function test_speed_remove_metabox() {
    $class = $this->speed->get_sub_features()['aucor_core_speed_remove_metabox'];
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
     * - check meta box is present (should be)
     * - run callback function
     * - check meta box has been removed
     */
    global $wp_meta_boxes;
    add_meta_box(
      'postcustom',
      'Test',
      'test_callback',
      'post',
      'normal',
      'core'
    );
    $this->assertArrayHasKey(
      'postcustom', $wp_meta_boxes['post']['normal']['core']
    );

    $class->aucor_core_remove_post_meta_metabox();

    $this->assertEmpty(
      $wp_meta_boxes['post']['normal']['core']['postcustom']
    );
  }

}
