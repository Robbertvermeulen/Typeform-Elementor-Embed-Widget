<?php
/**
 * Plugin Name: Typeform Embed for Elementor
 * Plugin URI: https://example.com/
 * Description: Embed Typeform forms in Elementor using custom fields
 * Version: 1.0.0
 * Author: Your Name
 * Author URI: https://example.com/
 * License: GPL v2 or later
 * Text Domain: typeform-embed-elementor
 * Elementor tested up to: 3.17.0
 * Elementor Pro tested up to: 3.17.0
 */

if (!defined('ABSPATH')) {
    exit;
}

define('TYPEFORM_EMBED_VERSION', '0.0.0');
define('TYPEFORM_EMBED_PATH', plugin_dir_path(__FILE__));
define('TYPEFORM_EMBED_URL', plugin_dir_url(__FILE__));

/**
 * Main plugin class
 */
class TypeformEmbedElementor {
    
    private static $instance = null;
    
    public static function instance() {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    public function __construct() {
        add_action('plugins_loaded', [$this, 'init']);
    }
    
    public function init() {
        // Check if Elementor is active
        if (!did_action('elementor/loaded')) {
            add_action('admin_notices', [$this, 'elementor_missing_notice']);
            return;
        }
        
        // Register widget
        add_action('elementor/widgets/register', [$this, 'register_widget']);
        
        // Register frontend scripts
        add_action('elementor/frontend/after_register_scripts', [$this, 'register_frontend_scripts']);
        
        // Enqueue frontend scripts
        add_action('elementor/frontend/after_enqueue_scripts', [$this, 'enqueue_frontend_scripts']);
    }
    
    public function elementor_missing_notice() {
        if (isset($_GET['activate'])) {
            unset($_GET['activate']);
        }
        
        $message = sprintf(
            esc_html__('"%1$s" requires "%2$s" to be installed and activated.', 'typeform-embed-elementor'),
            '<strong>' . esc_html__('Typeform Embed for Elementor', 'typeform-embed-elementor') . '</strong>',
            '<strong>' . esc_html__('Elementor', 'typeform-embed-elementor') . '</strong>'
        );
        
        printf('<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message);
    }
    
    public function register_widget($widgets_manager) {
        require_once TYPEFORM_EMBED_PATH . 'includes/widgets/typeform-widget.php';
        $widgets_manager->register(new \Typeform_Widget());
    }
    
    public function register_frontend_scripts() {
        // Use official Typeform CDN embed script
        wp_register_script(
            'typeform-embed-library',
            'https://embed.typeform.com/next/embed.js',
            [],
            null,
            true
        );
        
        // Register our custom script (simplified version)
        wp_register_script(
            'typeform-embed-widget',
            TYPEFORM_EMBED_URL . 'assets/js/typeform-embed-simple.min.js',
            ['jquery'],
            TYPEFORM_EMBED_VERSION,
            true
        );
        
        // Register styles
        wp_register_style(
            'typeform-embed-widget',
            TYPEFORM_EMBED_URL . 'assets/css/typeform-embed.css',
            [],
            TYPEFORM_EMBED_VERSION
        );
    }
    
    public function enqueue_frontend_scripts() {
        wp_enqueue_script('typeform-embed-library');
        wp_enqueue_script('typeform-embed-widget');
        wp_enqueue_style('typeform-embed-widget');
    }
}

// Initialize the plugin
TypeformEmbedElementor::instance();