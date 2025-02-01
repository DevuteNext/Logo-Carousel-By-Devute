<?php
namespace Devute\LogoCarousel;

if (!defined('ABSPATH')) {
    exit;
}

class Admin {
    public function __construct() {
        add_action('admin_menu', array($this, 'add_menu_pages'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_assets'));
        add_filter('admin_body_class', array($this, 'admin_body_class'));
    }

    public function add_menu_pages() {
        add_menu_page(
            __('Logo Carousel', 'logo-carousel-by-devute'),
            __('Logo Carousel', 'logo-carousel-by-devute'),
            'manage_options',
            'logo-carousel',
            array($this, 'render_dashboard_page'),
            DLCP_URL . 'assets/images/plugin-icon.svg',
            25
        );

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
            'edit.php?post_type=logo_slider'
        );

        add_submenu_page(
            'logo-carousel',
            __('Add New Slider', 'logo-carousel-by-devute'),
            __('Add New', 'logo-carousel-by-devute'),
            'manage_options',
            'post-new.php?post_type=logo_slider'
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
        <div class="wrap dlcp-dashboard">
            <div class="dlcp-header">
                <img src="<?php echo DLCP_URL; ?>assets/images/devute-logo.svg" alt="Devute" class="dlcp-logo">
                <h1><?php _e('Logo Carousel by Devute', 'logo-carousel-by-devute'); ?></h1>
            </div>

            <div class="dlcp-dashboard-content">
                <div class="dlcp-welcome-panel">
                    <h2><?php _e('Welcome to Logo Carousel', 'logo-carousel-by-devute'); ?></h2>
                    <p><?php _e('Create beautiful logo carousels in minutes with our easy-to-use plugin.', 'logo-carousel-by-devute'); ?></p>