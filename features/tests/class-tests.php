<?php
/**
 * Class Tests
 */
class Aucor_Core_Tests extends Aucor_Core_Feature {

  public function setup() {

    // var: key
    $this->set('key', 'aucor_core_tests');

    // var: name
    $this->set('name', 'Tests');

    // var: is_active
    $this->set('is_active', true);

  }

  /**
   * Initialize and add the sub_features to the $sub_features array
   */
  public function sub_features_init() {

    // var: sub_features
    $this->set('sub_features', array(
      'aucor_core_tests_style_guide'  => new Aucor_Core_Tests_Style_Guide,
    ));

  }

}
