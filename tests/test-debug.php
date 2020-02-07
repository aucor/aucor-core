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
     */

    // run callback function
    $content = $class->aucor_core_style_guide_markup('');

    // check that return value is empty
    $this->assertEmpty(
      $content
    );

    // set global $_GET variable
    $_GET['ac-debug'] = 'styleguide';

    // run callback function
    $content = $class->aucor_core_style_guide_markup('');

    // check that return value is not empty
    $this->assertNotEmpty(
      $content
    );

    // add filter to override content
    add_filter('aucor_core_custom_markup', function($arg) {
      $arg = 'Test markup';
      return $arg;
    });

    // run callback function
    $content = $class->aucor_core_style_guide_markup('');

    // check that returned value equals set content
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
     */

    // buffer output
    ob_start();

    // run callback function
    $class->aucor_core_wireframe();
    $output = ob_get_contents();
    ob_clean();

    // check that the return value is empty
    $this->assertEmpty(
      $output
    );

    // set the global $_GET variable
    $_GET['ac-debug'] = 'wireframe';

    // run callback function
    $class->aucor_core_wireframe();
    $output = ob_get_clean();

    // check the return value for keywords
    $this->assertStringContainsString(
      'outline: 1px solid !important;', $output
    );
    $this->assertStringContainsString(
      "links[i].href += '?ac-debug=wireframe';", $output
    );
  }

}
