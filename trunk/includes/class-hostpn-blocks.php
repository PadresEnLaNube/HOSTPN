<?php
/**
 * Gutenberg blocks.
 *
 * This class defines all Gutenberg blocks of the plugin.
 *
 * @link       padresenlanube.com/
 * @since      1.0.51
 * @package    HOSTPN
 * @subpackage HOSTPN/includes
 * @author     Padres en la Nube <info@padresenlanube.com>
 */
class HOSTPN_Blocks {
  /**
   * Register all blocks
   *
   * @since    1.0.51
   */
  public function register_blocks() {
    // Only register blocks if Gutenberg is available
    if (!function_exists('register_block_type')) {
      return;
    }

    // Register carousel block
    register_block_type('hostpn/carousel', array(
      'render_callback' => array($this, 'render_carousel_block'),
      'attributes' => array(
        'images' => array(
          'type' => 'array',
          'default' => array(),
        ),
        'postId' => array(
          'type' => 'number',
          'default' => 0,
        ),
        'showNav' => array(
          'type' => 'boolean',
          'default' => true,
        ),
        'showDots' => array(
          'type' => 'boolean',
          'default' => true,
        ),
        'showCounter' => array(
          'type' => 'boolean',
          'default' => false,
        ),
        'showAutoplay' => array(
          'type' => 'boolean',
          'default' => false,
        ),
        'autoplay' => array(
          'type' => 'boolean',
          'default' => false,
        ),
        'autoplaySpeed' => array(
          'type' => 'number',
          'default' => 5000,
        ),
        'speed' => array(
          'type' => 'number',
          'default' => 500,
        ),
        'loop' => array(
          'type' => 'boolean',
          'default' => true,
        ),
        'itemsDesktop' => array(
          'type' => 'number',
          'default' => 1,
        ),
        'itemsMobile' => array(
          'type' => 'number',
          'default' => 1,
        ),
      ),
    ));
  }

  /**
   * Render carousel block
   *
   * @param array $attributes Block attributes
   * @return string HTML output
   */
  public function render_carousel_block($attributes) {
    // Ensure images is an array
    $images = isset($attributes['images']) && is_array($attributes['images']) ? $attributes['images'] : array();

    // Get boolean attributes with proper defaults
    $show_nav = !isset($attributes['showNav']) || $attributes['showNav'] === true;
    $show_dots = !isset($attributes['showDots']) || $attributes['showDots'] === true;
    $show_counter = isset($attributes['showCounter']) && $attributes['showCounter'] === true;
    $show_autoplay = isset($attributes['showAutoplay']) && $attributes['showAutoplay'] === true;
    $autoplay = isset($attributes['autoplay']) && $attributes['autoplay'] === true;

    // Get numeric attributes with defaults
    $autoplay_speed = isset($attributes['autoplaySpeed']) ? intval($attributes['autoplaySpeed']) : 5000;
    $speed = isset($attributes['speed']) ? intval($attributes['speed']) : 500;
    $items_desktop = isset($attributes['itemsDesktop']) ? intval($attributes['itemsDesktop']) : 1;
    $items_mobile = isset($attributes['itemsMobile']) ? intval($attributes['itemsMobile']) : 1;

    // Get loop with default
    $loop = !isset($attributes['loop']) || $attributes['loop'] === true;

    // Check if images array is empty
    if (empty($images)) {
      return '<div class="hostpn-carousel-empty" style="padding: 40px; text-align: center; background: #f5f5f5; border-radius: 4px;">' . esc_html__('No images selected. Please select images from the block settings.', 'hostpn') . '</div>';
    }

    ob_start();
    ?>
    <div class="hostpn-carousel"
         data-auto-init="true"
         data-autoplay="<?php echo $autoplay ? 'true' : 'false'; ?>"
         data-autoplay-speed="<?php echo esc_attr($autoplay_speed); ?>"
         data-speed="<?php echo esc_attr($speed); ?>"
         data-loop="<?php echo $loop ? 'true' : 'false'; ?>"
         data-dots="<?php echo $show_dots ? 'true' : 'false'; ?>"
         data-nav="<?php echo $show_nav ? 'true' : 'false'; ?>"
         data-counter="<?php echo $show_counter ? 'true' : 'false'; ?>"
         data-items-desktop="<?php echo esc_attr($items_desktop); ?>"
         data-items-mobile="<?php echo esc_attr($items_mobile); ?>">
      <?php foreach ($images as $image): ?>
        <?php
        // Handle both array and object formats
        $image_url = '';
        $image_alt = '';

        if (is_array($image)) {
          $image_url = isset($image['url']) ? $image['url'] : '';
          $image_alt = isset($image['alt']) ? $image['alt'] : '';
        } elseif (is_object($image)) {
          $image_url = isset($image->url) ? $image->url : '';
          $image_alt = isset($image->alt) ? $image->alt : '';
        }

        if (empty($image_url)) continue;
        ?>
        <div>
          <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($image_alt); ?>">
        </div>
      <?php endforeach; ?>
    </div>
    <?php
    return ob_get_clean();
  }

  /**
   * Enqueue block editor assets
   *
   * @since    1.0.51
   */
  public function enqueue_block_editor_assets() {
    wp_enqueue_script(
      'hostpn-blocks',
      plugin_dir_url(dirname(__FILE__)) . 'assets/js/hostpn-blocks.js',
      array('wp-blocks', 'wp-element', 'wp-editor', 'wp-block-editor', 'wp-components', 'wp-i18n', 'wp-server-side-render'),
      filemtime(plugin_dir_path(dirname(__FILE__)) . 'assets/js/hostpn-blocks.js'),
      true
    );

    // Enqueue carousel styles in editor
    wp_enqueue_style(
      'hostpn-carousel-editor',
      plugin_dir_url(dirname(__FILE__)) . 'assets/css/hostpn-carousel.css',
      array(),
      filemtime(plugin_dir_path(dirname(__FILE__)) . 'assets/css/hostpn-carousel.css')
    );

    // Enqueue carousel script in editor for preview
    wp_enqueue_script(
      'hostpn-carousel-editor',
      plugin_dir_url(dirname(__FILE__)) . 'assets/js/hostpn-carousel.js',
      array('jquery'),
      filemtime(plugin_dir_path(dirname(__FILE__)) . 'assets/js/hostpn-carousel.js'),
      true
    );
  }
}
