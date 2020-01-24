<?php
/**
 * Class DebugTest
 *
 * @package Aucor_Core
 */

class DebugTest extends WP_UnitTestCase {

  private $debug;

  public function setUp() {
    parent::setUp();
    $this->debug = new Aucor_Core_Debug;
  }

  public function tearDown() {
    unset($this->debug);
    parent::tearDown();
  }

  // test debug feature

  public function test_debug() {
    $class = $this->debug;
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

  // test debug sub features

  public function test_debug_style_guide() {
    $class = $this->debug->get_sub_features()['aucor_core_debug_style_guide'];
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
     * - check that return value is empty
     * -- second part:
     * - set global $_GET variable
     * - run callback function
     * - check that return value is not empty
     * -- third part:
     * - add filter to override content
     * - check that returned value equals set content
     */
    $content = $class->aucor_core_style_guide_markup('');
    $this->assertEmpty(
      $content
    );

     $_GET['ac-debug'] = 'styleguide';

    $content = $class->aucor_core_style_guide_markup('');
    $this->assertNotEmpty(
      $content
    );

    add_filter('aucor_core_custom_markup', function($arg) {
      $arg = 'Test markup';
      return $arg;
    });

    $content = $class->aucor_core_style_guide_markup('');
    $this->assertSame(
      'Test markup', $content
    );
  }

  public function test_debug_wireframe() {
    $class = $this->debug->get_sub_features()['aucor_core_debug_wireframe'];
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
     * - buffer output
     * - run callback function
     * - check that the return value is empty
     * -- second part:
     * - set the global $_GET variable
     * - run callback function
     * - check the return value for keywords
     */

    ob_start();
    $class->aucor_core_wireframe();
    $output = ob_get_contents();
    ob_clean();

    $this->assertEmpty(
      $output
    );

    $_GET['ac-debug'] = 'wireframe';

    $class->aucor_core_wireframe();
    $output = ob_get_clean();

    $this->assertStringContainsString(
      'outline: 1px solid !important;', $output
    );
    $this->assertStringContainsString(
      "links[i].href += '?ac-debug=wireframe';", $output
    );
  }

}
