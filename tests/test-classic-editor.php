<?php
/**
 * Class ClassicEditorTest
 *
 * @package Aucor_Core
 */

class ClassicEditorTest extends WP_UnitTestCase {

  private $ce;

  public function setUp() {
    parent::setUp();
    $this->ce = new Aucor_Core_Classic_Editor;
  }

  public function tearDown() {
    unset($this->ce);
    parent::tearDown();
  }

  // test CE feature

  public function test_ce() {
    $class = $this->ce;
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

  // test CE sub features

  public function test_ce_tinymce() {
    $class = $this->ce->get_sub_features()['aucor_core_classic_editor_tinymce'];
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
     * - check that the callback function returns correct value
     */
    $args = array('wordpress_adv_hidden' => true);

    $this->assertFalse(
      $class->aucor_core_show_second_editor_row($args)['wordpress_adv_hidden']
    );
  }

}
