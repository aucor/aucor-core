<?php
/**
 * Class AdminTest
 *
 * @package Aucor_Core
 */

class AdminTest extends WP_UnitTestCase {

  var $admin;

  public function setUp() {
    parent::setup();
    $this->admin = new Aucor_Core_Admin;
  }

  // test admin class
  public function test_admin() {
    $class = $this->admin;
    // key
    $this->assertSame(
      'aucor_core_admin', $class->get_key()
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

  // test admin class features
	public function test_admin_gallery() {
    $class = $this->admin->get_sub_features()['aucor_core_admin_gallery'];
    // key
    $this->assertSame(
      'aucor_core_admin_gallery', $class->get_key()
    );
    // name
    $this->assertNotEmpty(
      $class->get_name()
    );
    // status
    $this->assertTrue(
      $class->is_active()
    );

    // run
    $args = array(
      'galleryDefaults' => array(
        'link'    => 'file',
        'size'    => 'medium',
        'columns' => '2',
      )
    );
    $this->assertEquals(
      $args, $class->aucor_core_gallery_defaults(array())
    );

  }

  public function test_admin_image_link() {
    $class = $this->admin->get_sub_features()['aucor_core_admin_image_link'];
    // key
    $this->assertSame(
      'aucor_core_admin_image_link', $class->get_key()
    );
    // name
    $this->assertNotEmpty(
      $class->get_name()
    );
    // status
    $this->assertTrue(
      $class->is_active()
    );

    // run


  }
}
