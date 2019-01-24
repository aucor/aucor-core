<?php
/**
 * Class Debug_Wireframe
 */
class Aucor_Core_Debug_Wireframe extends Aucor_Core_Sub_Feature {

  public function setup() {

    // var: key
    $this->set('key', 'aucor_core_debug_wireframe');

    // var: name
    $this->set('name', 'Adds outlines to all elements on page to help with visual debugging');

    // var: is_active
    $this->set('is_active', true);

  }

  /**
   * Run feature
   */
  public function run() {
    add_action('wp_head', array('Aucor_Core_Debug_Wireframe', 'aucor_core_wireframe'), 10);
  }

  /**
   * Adds outlines to all elements on page to help with visual debugging if the GET parameter "?ac-debug=wireframe" is present in the url
   */
  public static function aucor_core_wireframe() {
    if (isset($_GET['ac-debug']) && $_GET['ac-debug'] == 'wireframe') {
    ?>
    <style>
      * {
        outline: 1px solid !important;
      }
    </style>
    <?php
    }
  }
}
