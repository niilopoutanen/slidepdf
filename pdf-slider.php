<?php
namespace PDF_Slider;

/*
 * Plugin Name:       PDF Slider
 * Plugin URI:        https://github.com/niilopoutanen/pdf-slider
 * Description:       Load a PDF file into a embedded slider viewer.
 * Version:           0.0.1
 * Author:            Niilo Poutanen
 * Author URI:        https://poutanen.dev/
 * Text Domain:       pdf-slider
 */

if (!defined('ABSPATH')) {
    exit;
}

require_once plugin_dir_path(__FILE__) . 'ui.php';

function register_scripts()
{
    wp_register_script(
        'pdfjs',
        plugins_url('scripts/pdfjs/pdf.js', __FILE__),
        [],
        '5.4.530',
        true
    );

    wp_register_script(
        'pdf-slider',
        plugins_url('scripts/pdf-slider.js', __FILE__),
        ['pdfjs'],
        '1.0',
        true
    );

    wp_register_script(
        'swiper',
        plugins_url('scripts/swiper/swiper-bundle.min.js', __FILE__),
        [],
        '12.1.0',
        true
    );

    wp_register_style(
        'pdf-slider',
        plugins_url('styles.css', __FILE__),
        [],
        '1.0'
    );

    wp_register_style(
        'swiper',
        plugins_url('scripts/swiper/swiper.min.css', __FILE__),
        [],
        '12.1.0'
    );

    wp_register_style(
        'swiper-pagination',
        plugins_url('scripts/swiper/pagination-element.min.css', __FILE__),
        [],
        '12.1.0'
    );

}
add_action('wp_enqueue_scripts', '\PDF_Slider\register_scripts');

add_filter('script_loader_tag', function ($tag, $handle) {
    if (in_array($handle, ['pdfjs', 'pdf-slider'], true)) {
        return str_replace('<script ', '<script type="module" ', $tag);
    }
    return $tag;
}, 10, 2);


add_action('elementor/widgets/register', function ($widgets_manager) {
    if (defined('ELEMENTOR_PATH') && class_exists('\Elementor\Widget_Base')) {
        require_once plugin_dir_path(__FILE__) . 'elementor-widget.php';

        $widgets_manager->register(new Elementor_Widget());
    }
});

function render_shortcode($atts)
{
    if (empty($atts['src'])) {
        return '';
    }

    return UI::get_slider($atts['src']);
}

add_shortcode('pdf_slider', '\PDF_Slider\render_shortcode');
