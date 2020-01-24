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
     * -- first part:
     * - mock args
     * - run callback function
     * - check that the return value is correct
     * -- second part:
     * - mock args
     * - run callback function
     * - check that the return value is correct
     */
    $excerpt = 'Test string';

    $this->assertSame(
      '...', $class->aucor_core_excerpt_more($excerpt)
    );

    $length = 100;

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
     * -- first part:
     * - run callback function
     * - check that the return value contains correct string
     * -- second part:
     * - mock args
     * - run callback function
     * - check that correct strings have been removed
     */

    $this->assertStringContainsString(
      ' itemprop="relatedLink/pagination" ', $class->aucor_core_next_posts_attributes('')
    );

    $tag1 = "type='text/javascript' ";

    $this->assertEmpty(
      $class->aucor_core_cleanup_script_tags($tag1)
    );

    $tag2 = 'type="text/javascript" ';

    $this->assertEmpty(
      $class->aucor_core_cleanup_script_tags($tag2)
    );

    $tag3 = 'Test ';

    $this->assertNotEmpty(
      $class->aucor_core_cleanup_script_tags($tag3)
    );

  }

}
