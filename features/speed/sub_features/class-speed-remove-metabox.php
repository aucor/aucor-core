<?php
/**
 * Class Speed_Remove_Metabox
 */
class Aucor_Core_Speed_Remove_Metabox extends Aucor_Core_Sub_Feature {

  public function setup() {

    // var: key
    $this->set('key', 'aucor_core_speed_remove_metabox');

    // var: name
    $this->set('name', 'Remove slow performing post_meta metabox');

    // var: is_active
    $this->set('is_active', true);

  }

  /**
   * Run feature
   */
  public function run() {
    add_action('add_meta_boxes', array('Aucor_Core_Speed_Remove_Metabox', 'aucor_core_remove_post_meta_metabox'));
  }

  /**
   * Remove slow performing post_meta metabox
   */
  public static function aucor_core_remove_post_meta_metabox() {
    remove_meta_box('postcustom', null, 'normal');
  }

}
