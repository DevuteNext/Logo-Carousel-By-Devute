<?php
namespace Devute\LogoCarousel;

class Shortcode {
    public function __construct() {
        add_shortcode('logo_carousel', array($this, 'render_shortcode'));
        add_action('add_meta_boxes', array($this, 'add_shortcode_meta_box'));
        add_action('add_meta_boxes', array($this, 'add_logo_images_meta_box'));
        add_action('save_post_logo_slider', array($this, 'save_logo_images_meta'));
    }

    public function render_shortcode($atts) {
        $atts = shortcode_atts(array(
            'id' => '',
            'speed' => '25s',
            'pause_hover' => 'true',
            'show_grayscale' => 'true'
        ), $atts);

        if (empty($atts['id'])) {
            return '<p class="dlcp-error">' . __('Please provide a slider ID', 'logo-carousel-by-devute') . '</p>';
        }

        $logo_images = get_post_meta($atts['id'], 'logo_images', true);
        
        if (empty($logo_images)) {
            return '<p class="dlcp-error">' . __('No images found in this slider', 'logo-carousel-by-devute') . '</p>';
        }

        ob_start();
        ?>
        <div class="dlcp-carousel-wrapper" 
             data-speed="<?php echo esc_attr($atts['speed']); ?>"
             data-pause-hover="<?php echo esc_attr($atts['pause_hover']); ?>"
             data-grayscale="<?php echo esc_attr($atts['show_grayscale']); ?>">
            <div class="dlcp-carousel">
                <div class="dlcp-track">
                    <?php foreach ($logo_images as $image): ?>
                        <div class="dlcp-slide">
                            <?php 
                            $img_url = wp_get_attachment_image_src($image['id'], 'full');
                            if ($img_url): 
                            ?>
                                <img src="<?php echo esc_url($img_url[0]); ?>"
                                     alt="<?php echo esc_attr($image['caption']); ?>"
                                     loading="lazy">
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                    
                    <?php // Clone slides for infinite effect
                    foreach ($logo_images as $image): ?>
                        <div class="dlcp-slide">
                            <?php 
                            $img_url = wp_get_attachment_image_src($image['id'], 'full');
                            if ($img_url): 
                            ?>
                                <img src="<?php echo esc_url($img_url[0]); ?>"
                                     alt="<?php echo esc_attr($image['caption']); ?>"
                                     loading="lazy">
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }

    public function add_shortcode_meta_box() {
        add_meta_box(
            'dlcp_shortcode_info',
            __('Shortcode', 'logo-carousel-by-devute'),
            array($this, 'render_shortcode_meta_box'),
            'logo_slider',
            'side',
            'high'
        );
    }

    public function render_shortcode_meta_box($post) {
        ?>
        <div class="dlcp-shortcode-info">
            <p><?php _e('Use this shortcode to display the slider:', 'logo-carousel-by-devute'); ?></p>
            <code>[logo_carousel id="<?php echo $post->ID; ?>"]</code>
            
            <p><?php _e('Available Parameters:', 'logo-carousel-by-devute'); ?></p>
            <ul>
                <li><code>speed="25s"</code></li>
                <li><code>pause_hover="true/false"</code></li>
                <li><code>show_grayscale="true/false"</code></li>
            </ul>
            
            <p><?php _e('Example with all parameters:', 'logo-carousel-by-devute'); ?></p>
            <code>[logo_carousel id="<?php echo $post->ID; ?>" speed="20s" pause_hover="true" show_grayscale="true"]</code>
        </div>
        <?php
    }

    public function add_logo_images_meta_box() {
        add_meta_box(
            'dlcp_logo_images',
            __('Logo Images', 'logo-carousel-by-devute'),
            array($this, 'render_logo_images_meta_box'),
            'logo_slider',
            'normal',
            'high'
        );
    }

    public function render_logo_images_meta_box($post) {
        wp_nonce_field('dlcp_logo_images', 'dlcp_logo_images_nonce');
        $logo_images = get_post_meta($post->ID, 'logo_images', true);
        ?>
        <div class="dlcp-logo-images-wrapper">
            <div class="dlcp-actions">
                <button type="button" class="button button-primary" id="dlcp-add-images">
                    <?php _e('Add Images', 'logo-carousel-by-devute'); ?>
                </button>
            </div>

            <div id="dlcp-images-container" class="dlcp-images-grid">
                <?php
                if (!empty($logo_images)) {
                    foreach ($logo_images as $image) {
                        $this->render_image_item($image);
                    }
                }
                ?>
            </div>
        </div>

        <script type="text/template" id="dlcp-image-template">
            <?php
            $template_image = array(
                'id' => '{{id}}',
                'url' => '{{url}}',
                'caption' => '{{caption}}'
            );
            $this->render_image_item($template_image);
            ?>
        </script>
        <?php
    }

    private function render_image_item($image) {
        ?>
        <div class="dlcp-image-item" data-id="<?php echo esc_attr($image['id']); ?>">
            <div class="dlcp-image-preview">
                <img src="<?php echo esc_url($image['url']); ?>">
            </div>
            <input type="text" 
                   name="logo_images[<?php echo esc_attr($image['id']); ?>][caption]" 
                   value="<?php echo esc_attr($image['caption']); ?>"
                   placeholder="<?php esc_attr_e('Image Caption', 'logo-carousel-by-devute'); ?>">
            <button type="button" class="button dlcp-remove-image">
                <?php _e('Remove', 'logo-carousel-by-devute'); ?>
            </button>
            <input type="hidden" 
                   name="logo_images[<?php echo esc_attr($im