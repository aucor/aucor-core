<?php
/**
 * Class LocalizationTest
 *
 * @package Aucor_Core
 */

class LocalizationTest extends WP_UnitTestCase {

  private $local;

  public function setUp() {
    parent::setUp();
    $this->local = new Aucor_Core_Localization;
  }

  public function tearDown() {
    unset($this->local);
    parent::tearDown();
  }

  // test localization feature

  public function test_localization() {
    $class = $this->local;
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

  // test localization sub features

  public function test_localization_polyfill() {
    $class = $this->local->get_sub_features()['aucor_core_localization_polyfill'];
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
     * - nothing is actually run in the run function, but the "semi-class" provides polyfill functions for the Polylang plugin
     * -- first part:
     * - mock args
     * - check that function return correct values on valid and invalid locales
     * -- second part:
     * - mock args
     * - check functions existence and that the return value is correct
     */
    add_filter('locale', function($locale) {
      $locale = 'a'; // invalid locale

      return $locale;
    });

    $this->assertSame(
      '', aucor_core_get_site_locale()
    );

    add_filter('locale', function($locale) {
      $locale = 'en_US';

      return $locale;
    });

    $this->assertSame(
      'en', aucor_core_get_site_locale()
    );

    $this->assertTrue(
      function_exists('pll__')
    );
    $string = 'Test';
    $this->assertSame(
      $string, pll__($string)
    );

    $this->assertTrue(
      function_exists('pll_e')
    );
    $string2 = 'Test 2';
    ob_start();
    pll_e($string2);
    $output = ob_get_contents();
    ob_clean();
    $this->assertSame(
      $string2, $output
    );

    $this->assertTrue(
      function_exists('pll_esc_html__')
    );
    $html = '<a href="http://www.example.com/">A link</a>';
    $this->assertSame(
      '&lt;a href=&quot;http://www.example.com/&quot;&gt;A link&lt;/a&gt;', pll_esc_html__($html)
    );

    $this->assertTrue(
      function_exists('pll_esc_html_e')
    );
    $html2 = '<div class="example">A div</div>';
    pll_esc_html_e($html2);
    $output2 = ob_get_contents();
    ob_clean();
    $this->assertSame(
      '&lt;div class=&quot;example&quot;&gt;A div&lt;/div&gt;', $output2
    );

    $this->assertTrue(
      function_exists('pll_esc_attr__')
    );
    $attr = 'A & B';
    $this->assertSame(
      'A &amp; B', pll_esc_attr__($attr)
    );

    $this->assertTrue(
      function_exists('pll_esc_attr_e')
    );
    $attr2 = '"Quotes"';
    pll_esc_attr_e($attr2);
    $output3 = ob_get_clean();
    $this->assertSame(
      '&quot;Quotes&quot;', $output3
    );

    $this->assertTrue(
      function_exists('pll_current_language')
    );
    add_filter('locale', function($locale) {
      $locale = 'fi';

      return $locale;
    });
    $this->assertSame(
      'fi', pll_current_language()
    );

    $this->assertTrue(
      function_exists('pll_get_post_language')
    );
    add_filter('locale', function($locale) {
      $locale = 'sv_SE';

      return $locale;
    });
    $post = $this->factory->post->create();
    $this->assertSame(
      'sv', pll_get_post_language($post)
    );

    $this->assertTrue(
      function_exists('pll_get_post')
    );
    $post2 = $this->factory->post->create();
    $this->assertSame(
      $post2, pll_get_post($post2, 'test_slug')
    );

    $this->assertTrue(
      function_exists('pll_get_term')
    );
    $term = $this->factory->term->create();
    $this->assertSame(
      $term, pll_get_term($term, 'test_slug2')
    );

    $this->assertTrue(
      function_exists('pll_translate_string')
    );
    $string3 = 'Test 3';
    $this->assertSame(
      $string3, pll_translate_string($string3, 'test_lang')
    );

    $this->assertTrue(
      function_exists('pll_home_url')
    );
    $this->assertSame(
      get_home_url(), pll_home_url('test_slug3')
    );

  }

  public function test_localization_string_translations() {
    $class = $this->local->get_sub_features()['aucor_core_localization_string_translations'];
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
     * - check that the mock function contains the filtered values
     * -- second part:
     * - check that functions exist and that return correct value
     * - the ask__ and asv__ functions will throw an E_USER_WARNING on invalid inputs, so
     * place them last and before running them, handle warning with custom function
     */
    add_filter('aucor_core_pll_register_strings', function($string_arr){
      $string_arr = array(
        'key 1' => 'value 1',
        'key 2' => 'value 2'
      );
      return $string_arr;
    });

    $class->aucor_core_string_registration();

    global $pll_strings;
    $blog_info = get_bloginfo();

    $args = array(
      'key 1' => array(
        'value'      => 'value 1',
        'group_name' => $blog_info,
      ),
      'key 2' => array(
        'value'      => 'value 2',
        'group_name' => $blog_info,
      )
    );

    $this->assertSame(
      $args, $pll_strings
    );

    $this->assertTrue(
      function_exists('ask__')
    );
    $this->assertSame(
      'value 1', ask__('key 1')
    );
    $this->assertSame(
      'value 2', ask__('key 2', 'fi')
    );

    $this->assertTrue(
      function_exists('ask_e')
    );
    ob_start();
    ask_e('key 1');
    $output = ob_get_contents();
    ob_clean();
    $this->assertSame(
      'value 1', $output
    );

    $this->assertTrue(
      function_exists('asv__')
    );
    $this->assertSame(
      'value 1', asv__('value 1')
    );
    $this->assertSame(
      'value 2', asv__('value 2', 'fi')
    );

    $this->assertTrue(
      function_exists('asv_e')
    );
    asv_e('value 2');
    $output = ob_get_clean();
    $this->assertSame(
      'value 2', $output
    );

    // testing the invalid inputs that throw a warning
    set_error_handler('handle_debug_msg_user_warning', E_USER_WARNING);

    $this->assertSame(
      'key 3', ask__('key 3')
    );

    $this->assertSame(
      'value 3', asv__('value 3')
    );

    restore_error_handler();
  }

}

function handle_debug_msg_user_warning($errno, $errstr) {
  $test = new WP_UnitTestCase;
  $test->assertSame(
    E_USER_WARNING, $errno
  );

  $test->assertStringContainsString(
    'Localization error - Missing string by', $errstr
  );
}

global $pll_strings;
$pll_strings = array();

// mock pll_register_string function
function pll_register_string($key, $value, $group_name) {
  global $pll_strings;
  $pll_strings[$key] = array('value' => $value, 'group_name' => $group_name);
}
