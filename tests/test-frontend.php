<?php
/**
 * Class FrontEndTest
 *
 * @package Aucor_Core
 */

class FrontEndTest extends WP_UnitTestCase {

  private $front_end;

  public function setUp() {
    parent::setUp();
    $this->front_end = new Aucor_Core_Front_End;
  }

  public function tearDown() {
    unset($this->front_end);
    parent::tearDown();
  }

  // test front end feature

  public function test_front_end() {
    $class = $this->front_end;
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

  // test front end sub features

  public function test_front_end_excerpt() {
    $class = $this->front_end->get_sub_features()['aucor_core_front_end_excerpt'];
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
      10, has_filter('excerpt_more', array($class, 'aucor_core_excerpt_more'))
    );
    $this->assertSame(
      10, has_filter('excerpt_length', array($class, 'aucor_core_excerpt_length'))
    );

    // AUCOR_CORE_EXCERPT_MORE()

     // mock args
    $excerpt = 'Test string';

    // check that the return value is correct
    $this->assertSame(
      '...', $class->aucor_core_excerpt_more($excerpt)
    );

    // AUCOR_CORE_EXCERPT_LENGTH()

    // mock args
    $length = 100;

    // check that the return value is correct
    $this->assertEquals(
     20, $class->aucor_core_excerpt_length($length)
    );
  }

  public function test_front_end_html_fixes() {
    $class = $this->front_end->get_sub_features()['aucor_core_front_end_html_fixes'];
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
      10, has_filter('next_posts_link_attributes', array($class, 'aucor_core_next_posts_attributes'))
    );
    $this->assertSame(
      10, has_filter('script_loader_tag', array($class, 'aucor_core_cleanup_script_tags'))
    );

    // AUCOR_CORE_NEXT_POSTS_ATTRIBUTES()

    // check that the return value contains correct string
    $this->assertStringContainsString(
      ' itemprop="relatedLink/pagination" ', $class->aucor_core_next_posts_attributes('')
    );

    // AUCOR_CORE_CLEANUP_SCRIPT_TAGS()

    // mock args
    $tag1 = "type='text/javascript' ";
    $tag2 = 'type="text/javascript" ';
    $tag3 = 'Test ';

    // check that the strings has been removed
    $this->assertEmpty(
      $class->aucor_core_cleanup_script_tags($tag1)
    );
    $this->assertEmpty(
      $class->aucor_core_cleanup_script_tags($tag2)
    );
    $this->assertNotEmpty(
      $class->aucor_core_cleanup_script_tags($tag3)
    );

  }

}
