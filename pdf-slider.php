<?php

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
function pdf_slider_register_scripts()
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
        plugins_url('scripts/swiper/swiper-bundle.min.css', __FILE__),
        [],
        '12.1.0'
    );
}
add_action('wp_enqueue_scripts', 'pdf_slider_register_scripts');

add_filter('script_loader_tag', function ($tag, $handle) {
    if (in_array($handle, ['pdfjs', 'pdf-slider'], true)) {
        return str_replace('<script ', '<script type="module" ', $tag);
    }
    return $tag;
}, 10, 2);

function pdf_slider_render_shortcode($atts)
{
    $atts = shortcode_atts([
        'src' => '',
    ], $atts);

    if (empty($atts['src']))
        return '';

    wp_enqueue_script('pdfjs');
    wp_enqueue_script('pdf-slider');
    wp_enqueue_script('swiper');
    wp_enqueue_style('pdf-slider');
    wp_enqueue_style('swiper');


    return
        '<div class="pdf-slider" data-pdf="' . esc_url($atts['src']) . '" data-worker="' . plugins_url('scripts/pdfjs/pdf.worker.js', __FILE__) . '">
        <div class="swiper">
            <div class="swiper-wrapper"></div>
            <div class="swiper-pagination"></div>
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
        </div>
    </div>';


}
add_shortcode('pdf_slider', 'pdf_slider_render_shortcode');
