<?php
namespace Devute\LogoCarousel;

class Frontend {
    public function __construct() {
        add_action('wp_enqueue_scripts', array($this, 'enqueue_frontend_assets'));
    }

    public function enqueue_frontend_assets() {
        wp_enqueue_style(
            'dlcp-frontend',
            DLCP_URL . 'assets/css/frontend.css',
            array(),
            DLCP_VERSION
        );

        wp_enqueue_script(
            'dlcp-frontend',
            DLCP_URL . 'assets/js/frontend.js',
            array(),
            DLCP_VERSION,
            true
        );
    }
}