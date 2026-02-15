<?php
namespace SlidePDF;

/*
 * Plugin Name:       SlidePDF
 * Plugin URI:        https://github.com/niilopoutanen/slidepdf
 * Description:       Load a PDF file into a embedded slider viewer.
 * Version:           0.2.5
 * Author:            Niilo Poutanen
 * Author URI:        https://poutanen.dev/
 * Text Domain:       slidepdf
 * License:           GPLv2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Domain path:       /languages
 */

if (!defined('ABSPATH')) {
    exit;
}

require_once plugin_dir_path(__FILE__) . 'config.php';
require_once plugin_dir_path(__FILE__) . 'ui.php';
require_once plugin_dir_path(__FILE__) . 'admin-page.php';

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
        'swiper',
        plugins_url('scripts/swiper/swiper-bundle.min.js', __FILE__),
        [],
        '12.1.0',
        true
    );

    wp_add_inline_script('swiper', 'window.SlidePDFSwiper = window.Swiper;', 'after');

    wp_register_script(
        'slidepdf',
        plugins_url('scripts/slidepdf.js', __FILE__),
        ['pdfjs', 'swiper'],
        '1.0',
        true
    );

    wp_register_style(
        'slidepdf',
        plugins_url('styles/styles.css', __FILE__),
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


    wp_localize_script(
        'slidepdf',
        'SlidePDFData',
        [
            'wasmUrl' => plugins_url('scripts/pdfjs/wasm/', __FILE__),
            'workerUrl' => plugins_url('scripts/pdfjs/pdf.worker.js', __FILE__),
        ]
    );


}
add_action('wp_enqueue_scripts', '\SlidePDF\register_scripts');

add_filter('script_loader_tag', function ($tag, $handle) {
    if (in_array($handle, ['pdfjs', 'slidepdf'], true)) {
        return str_replace('<script ', '<script type="module" ', $tag);
    }
    return $tag;
}, 10, 2);

add_action('plugins_loaded', function () {
    load_plugin_textdomain(
        'slidepdf',
        false,
        dirname(plugin_basename(__FILE__)) . '/languages'
    );
});


add_action('elementor/widgets/register', function ($widgets_manager) {
    if (defined('ELEMENTOR_PATH') && class_exists('\Elementor\Widget_Base')) {
        require_once plugin_dir_path(__FILE__) . 'elementor-widget.php';

        $widgets_manager->register(new Elementor_Widget());
    }
});

add_action('admin_enqueue_scripts', function ($hook) {
    if ($hook !== 'tools_page_slidepdf') {
        return;
    }

    wp_enqueue_style(
        'slidepdf-admin',
        plugins_url('styles/admin-styles.css', __FILE__),
        [],
        '1.0'
    );
});

function render_shortcode($atts)
{
    wp_enqueue_script('pdfjs');
    wp_enqueue_script('slidepdf');
    wp_enqueue_style('slidepdf');

    if (empty($atts['src'])) {
        return '';
    }

    $config = \SlidePDF\Config::get();

    if (!empty($atts['page'])) {
        $page = intval($atts['page'] ?? 1);
        return \SlidePDF\UI::get_single($atts['src'], $page);
    }

    wp_enqueue_script('swiper');
    wp_enqueue_style('swiper');
    wp_enqueue_style('swiper-pagination');
    return \SlidePDF\UI::get_slider($atts['src'], $config);
}

add_shortcode('slidepdf', '\SlidePDF\render_shortcode');

add_action('admin_init', ['\SlidePDF\Config', 'register']);
