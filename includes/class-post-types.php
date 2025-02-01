<?php
namespace Devute\LogoCarousel;

if (!defined('ABSPATH')) {
    exit;
}

class PostTypes {
    public function __construct() {
        add_action('init', array($this, 'register_post_types'));
        add_filter('manage_logo_slider_posts_columns', array($this, 'add_custom_columns'));
        add_action('manage_logo_slider_posts_custom_column', array($this, 'render_custom_columns'), 10, 2);
    }

    public function register_post_types() {
        $labels = array(
            'name'                  => _x('Logo Sliders', 'Post Type General Name', 'logo-carousel-by-devute'),
            'singular_name'         => _x('Logo Slider', 'Post Type Singular Name', 'logo-carousel-by-devute'),
            'menu_name'             => __('Logo Sliders', 'logo-carousel-by-devute'),
            'name_admin_bar'        => __('Logo Slider', 'logo-carousel-by-devute'),
            'archives'              => __('Slider Archives', 'logo-carousel-by-devute'),
            'attributes'            => __('Slider Attributes', 'logo-carousel-by-devute'),
            'parent_item_colon'     => __('Parent Slider:', 'logo-carousel-by-devute'),
            'all_items'             => __('All Sliders', 'logo-carousel-by-devute'),
            'add_new_item'          => __('Add New Slider', 'logo-carousel-by-devute'),
            'add_new'               => __('Add New', 'logo-carousel-by-devute'),
            'new_item'              => __('New Slider', 'logo-carousel-by-devute'),
            'edit_item'             => __('Edit Slider', 'logo-carousel-by-devute'),
            'update_item'           => __('Update Slider', 'logo-carousel-by-devute'),
            'view_item'             => __('View Slider', 'logo-carousel-by-devute'),
            'view_items'            => __('View Sliders', 'logo-carousel-by-devute'),
            'search_items'          => __('Search Slider', 'logo-carousel-by-devute'),
            'not_found'             => __('Not found', 'logo-carousel-by-devute'),
            'not_found_in_trash'    => __('Not found in Trash', 'logo-carousel-by-devute'),
            'featured_image'        => __('Featured Image', 'logo-carousel-by-devute'),
            'set_featured_image'    => __('Set featured image', 'logo-carousel-by-devute'),
            'remove_featured_image' => __('Remove featured image', 'logo-carousel-by-devute'),
            'use_featured_image'    => __('Use as featured image', 'logo-carousel-by-devute'),
            'insert_into_item'      => __('Insert into slider', 'logo-carousel-by-devute'),
            'uploaded_to_this_item' => __('Uploaded to this slider', 'logo-carousel-by-devute'),
            'items_list'            => __('Sliders list', 'logo-carousel-by-devute'),
            'items_list_navigation' => __('Sliders list navigation', 'logo-carousel-by-devute'),
            'filter_items_list'     => __('Filter sliders list', 'logo-carousel-by-devute'),
        );

        $args = array(
            'label'               => __('Logo Slider', 'logo-carousel-by-devute'),
            'description'         => __('Logo Carousel Sliders', 'logo-carousel-by-devute'),
            'labels'             => $labels,
            'supports'           => array('title'),
            'hierarchical'       => false,
            'public'             => true,
            'show_ui'            => true,
            'show_in_menu'       => false,
            'menu_position'      => 25,
            'menu_icon'          => 'dashicons-images-alt2',
            'show_in_admin_bar'  => true,
            'show_in_nav_menus'  => false,
            'can_export'         => true,
            'has_archive'        => false,
            'exclude_from_search'=> true,
            'publicly_queryable' => false,
            'capability_type'    => 'post',
            'show_in_rest'       => false,
        );

        register_post_type('logo_slider', $args);
    }

    public function add_custom_columns($columns) {
        $new_columns = array();
        $new_columns['cb'] = $columns['cb'];
        $new_columns['title'] = $columns['title'];
        $new_columns['shortcode'] = __('Shortcode', 'logo-carousel-by-devute');
        $new_columns['logos'] = __('