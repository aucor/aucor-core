<?php
/**
 * Class Tests_Style_Guide
 */
class Aucor_Core_Tests_Style_Guide extends Aucor_Core_Sub_Feature {

  public function setup() {

    // var: key
    $this->set('key', 'aucor_core_tests_style_guide');

    // var: name
    $this->set('name', 'Add test markup to a page');

    // var: is_active
    $this->set('is_active', true);

  }

  /**
   * Run feature
   */
  public function run() {
    add_filter('the_content', array('Aucor_Core_Tests_Style_Guide', 'aucor_core_style_guide_markup'));
  }

  /**
   * Add style guide test markup if the GET parameter "?aucor_core=style_guide" is present in the url.
   * The aucor_core_custom_markup filter makes it possible to replace the default with custom markup
   *
   * @param string content from the_content()
   *
   * @return string style guide or custom markup
   */
  public static function aucor_core_style_guide_markup($content) {
    if (isset($_GET['aucor_core']) && $_GET['aucor_core'] == 'style_guide') {
      // default style guide markup
      $content = '
      <h1>Heading 1</h1>
      <p>Paragraph - Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin quis massa tempor, tincidunt massa convallis, consequat massa. Curabitur eget erat libero. Ut ornare mollis suscipit. Praesent eu odio vestibulum, hendrerit nisi a, euismod urna. Sed eget diam ac purus malesuada consectetur.</p>
      <h2>Heading 2</h2>
      <p>Paragraph - Aenean id erat ut justo faucibus sollicitudin. Sed facilisis quam vitae mauris vehicula, id elementum dui mollis. Cras commodo neque id lorem vehicula, vel aliquet arcu volutpat. Duis consequat ligula elit, eget pellentesque diam molestie nec. Phasellus facilisis vulputate dui non luctus.</p>
      <h3>Heading 3</h3>
      <p>Paragraph - Nulla efficitur justo in turpis fermentum, eu pellentesque justo sagittis. Morbi pretium elit sed interdum sodales. Phasellus a dui mattis, sollicitudin mauris in, convallis est. Nullam a urna et est ultrices aliquet.</p>
      <p>Paragraph - Cras aliquam sed risus ac hendrerit. Donec nisi nisi, dapibus a dui eu, tristique faucibus felis. Quisque eleifend sit amet felis eget volutpat. Proin et cursus ex.</p>
      <h4>Heading 4</h4>
      <p>Paragraph - Aenean nec maximus augue. Nullam ac dapibus urna. Nunc dignissim magna leo. Duis at convallis nisi. Pellentesque eu congue odio. Proin imperdiet eros a tincidunt dignissim. Nam euismod id metus vitae maximus. Vestibulum in nulla vulputate, vehicula odio elementum, tincidunt risus.</p>
      <h5>Heading 5</h5>
      </p>Paragraph - Curabitur nibh sem, semper quis pellentesque a, mollis non elit. Mauris varius dolor ut eros placerat, ac ultricies urna rhoncus. Aliquam purus libero, gravida et laoreet in, pretium eget urna.</p>
      <p>Paragraph - Vestibulum nec ligula nulla. Nullam sed iaculis velit, dapibus aliquam felis. Praesent quis egestas justo.</p>
      <h6>Heading 6</h6>
      <p>Paragraph - Vivamus interdum metus eros, id semper libero euismod consectetur. Sed ac magna tincidunt, accumsan ligula sit amet, fringilla sapien.</p>
      <blockquote>Blockquote - Sed sollicitudin tristique cursus. Nullam vel libero quis neque molestie ornare. Quisque sollicitudin nulla id pulvinar dictum.</blockquote>
      <a href="#">Anchor</a>
      <ol>
      <li>Ordered</li>
      <li>List</li>
      </ol>
      <strong>Strong</strong>
      <ul>
      <li>Unordered</li>
      <li>List</li>
      </ul>
      <em>Emphasis</em>
      ';
      // (possibly) override with custom markup from theme/plugin
      $content = apply_filters('aucor_core_custom_markup', $content);
    }
    return $content;
  }
}
