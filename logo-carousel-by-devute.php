<?php
/**
 * Plugin Name: Logo Carousel By Devute
 * Plugin URI: https://devute.com/plugins/logo-carousel
 * Description: A flexible and powerful logo carousel plugin to showcase your clients, partners, or any other logos in a beautiful slider.
 * Version: 1.0.0
 * Author: Devute
 * Author URI: https://devute.com
 * Text Domain: logo-carousel-by-devute
 * Domain Path: /languages
 * Requires at least: 5.8
 * Requires PHP: 7.2
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('DLCP_VERSION', '1.0.0');
define('DLCP_FILE', __FILE__);
define('DLCP_PATH', plugin_dir_path(DLCP_FILE));
define('DLCP_URL', plugin_dir_url(DLCP_FILE));
define('DLCP_BASENAME', plugin_basename(DLCP_FILE));

class LogoCarouselByDevute {
    private static $instance = null;

    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        add_action('plugins_loaded', array($this, 'init'));
    }

    public function init() {
        // Load text domain
        load_plugin_textdomain('logo-carousel-by-devute', false, dirname(DLCP_BASENAME) . '/languages');
        
        // Register post type
        add_action('init', array($this, 'register_post_type'));
        
        // Add admin menu
        add_action('admin_menu', array($this, 'add_admin_menu'));
        
        // Add meta boxes
        add_action('add_meta_boxes', array($this, 'add_meta_boxes'));
        
        // Save post meta
        add_action('save_post_logo_slider', array($this, 'save_slider_meta'));
        
        // Register shortcode
        add_shortcode('logo_carousel', array($this, 'render_shortcode'));
        
        // Enqueue scripts and styles
        add_action('admin_enqueue_scripts', array($this, 'admin_scripts'));
        add_action('wp_enqueue_scripts', array($this, 'frontend_scripts'));
    }

    public function register_post_type() {
        $labels = array(
            'name'               => __('Logo Sliders', 'logo-carousel-by-devute'),
            'singular_name'      => __('Logo Slider', 'logo-carousel-by-devute'),
            'add_new'           => __('Add New', 'logo-carousel-by-devute'),
            'add_new_item'      => __('Add New Slider', 'logo-carousel-by-devute'),
            'edit_item'         => __('Edit Slider', 'logo-carousel-by-devute'),
            'new_item'          => __('New Slider', 'logo-carousel-by-devute'),
            'all_items'         => __('All Sliders', 'logo-carousel-by-devute'),
            'view_item'         => __('View Slider', 'logo-carousel-by-devute'),
            'search_items'      => __('Search Sliders', 'logo-carousel-by-devute'),
            'not_found'         => __('No sliders found', 'logo-carousel-by-devute'),
            'not_found_in_trash'=> __('No sliders found in Trash', 'logo-carousel-by-devute'),
            'menu_name'         => __('Logo Carousel', 'logo-carousel-by-devute')
        );

        $args = array(
            'labels'              => $labels,
            'public'              => true,
            'show_ui'             => true,
            'show_in_menu'        => false, // Don't show in sidebar
            'capability_type'     => 'post',
            'hierarchical'        => false,
            'supports'            => array('title'),
            'has_archive'         => false,
            'rewrite'            => array('slug' => 'logo-slider')
        );

        register_post_type('logo_slider', $args);
    }

    public function add_admin_menu() {
        // Add main menu
        add_menu_page(
            __('Logo Carousel', 'logo-carousel-by-devute'),
            __('Logo Carousel', 'logo-carousel-by-devute'),
            'manage_options',
            'logo-carousel',
            array($this, 'render_dashboard_page'),
            'dashicons-images-alt2',
            25
        );

        // Add submenu pages
        add_submenu_page(
            'logo-carousel',
            __('Dashboard', 'logo-carousel-by-devute'),
            __('Dashboard', 'logo-carousel-by-devute'),
            'manage_options',
            'logo-carousel',
            array($this, 'render_dashboard_page')
        );

        add_submenu_page(
            'logo-carousel',
            __('All Sliders', 'logo-carousel-by-devute'),
            __('All Sliders', 'logo-carousel-by-devute'),
            'manage_options',
            'edit.php?post_type=logo_slider',
            null
        );

        add_submenu_page(
            'logo-carousel',
            __('Add New Slider', 'logo-carousel-by-devute'),
            __('Add New', 'logo-carousel-by-devute'),
            'manage_options',
            'post-new.php?post_type=logo_slider',
            null
        );

        add_submenu_page(
            'logo-carousel',
            __('Settings', 'logo-carousel-by-devute'),
            __('Settings', 'logo-carousel-by-devute'),
            'manage_options',
            'logo-carousel-settings',
            array($this, 'render_settings_page')
        );
    }

    public function render_dashboard_page() {
        ?>
        <div class="wrap">
            <h1><?php _e('Logo Carousel by Devute', 'logo-carousel-by-devute'); ?></h1>
            <div class="welcome-panel">
                <div class="welcome-panel-content">
                    <h2><?php _e('Welcome to Logo Carousel!', 'logo-carousel-by-devute'); ?></h2>
                    <p class="about-description"><?php _e('Create beautiful logo carousels in minutes.', 'logo-carousel-by-devute'); ?></p>
                    <div class="welcome-panel-column-container">
                        <div class="welcome-panel-column">
                            <h3><?php _e('Get Started', 'logo-carousel-by-devute'); ?></h3>
                            <a class="button button-primary" href="<?php echo admin_url('post-new.php?post_type=logo_slider'); ?>">
                                <?php _e('Create Your First Slider', 'logo-carousel-by-devute'); ?>
                            </a>
                        </div>
                        <div class="welcome-panel-column">
                            <h3><?php _e('Documentation', 'logo-carousel-by-devute'); ?></h3>
                            <a class="button" href="https://devute.com/docs/logo-carousel" target="_blank">
                                <?php _e('Read Documentation', 'logo-carousel-by-devute'); ?>
                            </a>
                        </div>
                        <div class="welcome-panel-column welcome-panel-last">
                            <h3><?php _e('Need Help?', 'logo-carousel-by-devute'); ?></h3>
                            <a class="button" href="https://devute.com/support" target="_blank">
                                <?php _e('Get Support', 'logo-carousel-by-devute'); ?>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }

    public function render_settings_page() {
        ?>
        <div class="wrap">
            <h1><?php _e('Logo Carousel Settings', 'logo-carousel-by-devute'); ?></h1>
            <form method="post" action="options.php">
                <?php
                settings_fields('logo_carousel_settings');
                do_settings_sections('logo_carousel_settings');
                submit_button();
                ?>
            </form>
        </div>
        <?php
    }

    public function admin_scripts($hook) {
        if ('post.php' === $hook || 'post-new.php' === $hook) {
            global $post;
            if ('logo_slider' === $post->post_type) {
                wp_enqueue_media();
                wp_enqueue_style('logo-carousel-admin', DLCP_URL . 'assets/css/admin.css', array(), DLCP_VERSION);
                wp_enqueue_script('logo-carousel-admin', DLCP_URL . 'assets/js/admin.js', array('jquery', 'jquery-ui-sortable'), DLCP_VERSION, true);
            }
        }
    }

    public function frontend_scripts() {
        wp_enqueue_style('logo-carousel-frontend', DLCP_URL . 'assets/css/frontend.css', array(), DLCP_VERSION);
        wp_enqueue_script('logo-carousel-frontend', DLCP_URL . 'assets/js/frontend.js', array(), DLCP_VERSION, true);
    }

    // Plugin activation
    public static function activate() {
        // Activation code here
        flush_rewrite_rules();
    }

    // Plugin deactivation
    public static function deactivate() {
        // Deactivation code here
        flush_rewrite_rules();
    }
}

// Initialize the plugin
function logo_carousel_by_devute() {
    return LogoCarouselByDevute::get_instance();
}

// Start the plugin
logo_carousel_by_devute();

// Register activation and deactivation hooks
register_activation_hook(__FILE__, array('LogoCarouselByDevute', 'activate'));
register_deactivation_hook(__FILE__, array('LogoCarouselByDevute', 'deactivate'));