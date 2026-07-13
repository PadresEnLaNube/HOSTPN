<?php
/**
 * Class HOSTPN_Popups
 * Handles popup functionality for the HOSTPN plugin
 */
class HOSTPN_Popups {
    /**
     * The single instance of the class
     */
    protected static $_instance = null;

    /**
     * Main HOSTPN_Popups Instance
     */
    public static function instance() {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
    
    /**
     * Open a popup
     */
    public static function open($content, $options = array()) {
        $defaults = array(
            'id' => uniqid('-popup-'),
            'class' => '',
            'closeButton' => true,
            'overlayClose' => true,
            'escClose' => true
        );

        $options = wp_parse_args($options, $defaults);

        ob_start();
        ?>
        <div id="<?php echo esc_attr($options['id']); ?>" class="hostpn-popup <?php echo esc_attr($options['class']); ?> hostpn-display-none-soft">
            <div class="hostpn-popup-overlay"></div>
            <div class="hostpn-popup-content">
                <?php if ($options['closeButton']) : ?>
                    <button type="button" class="hostpn-popup-close"><i class="material-icons-outlined">close</i></button>
                <?php endif; ?>
                <?php echo wp_kses_post($content); ?>
            </div>
        </div>
        <?php
        $html = ob_get_clean();

        return $html;
    }

    /**
     * Close a popup
     */
    public static function close($id = null) {
        $script = $id 
            ? "_Popups.close('" . esc_js($id) . "');"
            : "_Popups.close();";
            
        wp_add_inline_script('-popups', $script);
        return '';
    }
} 