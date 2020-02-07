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
     */

    // check filter hook
    $this->assertSame(
      10, has_filter('wp_revisions_to_keep', array($class, 'aucor_core_limit_revision_number'))
    );

    // AUCOR_CORE_LIMIT_REVISION_NUMBER()

    // mock args
    $number = 10;
    $post_id = 1;

    // check that the callback function returns correct values
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
     */

    // check action hook
    $this->assertSame(
      10, has_action('wp_default_scripts', array($class, 'aucor_core_move_jquery_into_footer'))
    );

    // AUCOR_CORE_MOVE_JQUERY_INTO_FOOTER()

    // mock args
    $scripts = new WP_Scripts();
    $scripts->add('jquery', false, array('jquery-core', 'jquery-migrate'));
    $scripts->add('jquery-core', '/jquery.js', array());
    $scripts->add('jquery-migrate', '/jquery-migrate.js', array());

    // run callback function
    $class->aucor_core_move_jquery_into_footer($scripts);

    // check that data has not beed added to the dependencies (group was not changed from default 0)
    $this->assertFalse(
      $scripts->get_data('jquery', 'group')
    );
    $this->assertFalse(
      $scripts->get_data('jquery-core', 'group')
    );
    $this->assertFalse(
      $scripts->get_data('jquery-migrate', 'group')
    );

    // move out of admin view (we set it previously in test-security as index.php)
    $this->go_to('/');

    // run callback function
    $class->aucor_core_move_jquery_into_footer($scripts);

    // check output
    $this->expectOutputRegex('/^(?:<script[^>]+><\\/script>\\n){2}$/');

    // check that nothing gets put in the done array
    $scripts->do_items('jquery', 0);
    $this->assertNotContains('jquery', $scripts->done);
    $this->assertNotContains('jquery-core', $scripts->done);
    $this->assertNotContains('jquery-migrate', $scripts->done);

    // check that the items get ut in the done array (the group has changed)
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
     */

    // check action hook
    $this->assertSame(
      10, has_action('init', array($class, 'aucor_core_disable_emojis'))
    );

    // AUCOR_CORE_DISABLE_EMOJIS()

    // run callback function
    $class->aucor_core_disable_emojis();

    // check that the filter and action hooks have been removed
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
     */

    // check action hook
    $this->assertSame(
      10, has_action('add_meta_boxes', array($class, 'aucor_core_remove_post_meta_metabox'))
    );

    // AUCOR_CORE_REMOVE_POST_META_METABOX()

    global $wp_meta_boxes;

    // mock metabox
    add_meta_box(
      'postcustom',
      'Test',
      'test_callback',
      'post',
      'normal',
      'core'
    );

    // check that the box is present
    $this->assertArrayHasKey(
      'postcustom', $wp_meta_boxes['post']['normal']['core']
    );

    // run callback function
    $class->aucor_core_remove_post_meta_metabox();

    // check that the meta box has been removed
    $this->assertEmpty(
      $wp_meta_boxes['post']['normal']['core']['postcustom']
    );
  }

}
