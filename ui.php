<?php
namespace SlidePDF;

class UI
{
    /**
     * @param string $pdf_url
     * @param array $options
     */


    public static function get_slider(string $pdf_url, array $config = []): string
    {
        wp_enqueue_script('pdfjs');
        wp_enqueue_script('slidepdf');
        wp_enqueue_script('swiper');
        wp_enqueue_style('slidepdf');
        wp_enqueue_style('swiper');

        $id = 'slidepdf-' . wp_unique_id();
        $style = $config['style'] ?? [];

        $swiper_options = esc_attr(wp_json_encode($config['swiper']));

        $chevron_svg = file_get_contents(
            plugin_dir_path(__FILE__) . 'assets/chevron.svg'
        );


        ob_start();
        ?>
        <style>
            #<?php echo $id; ?> {
                --slidepdf-button-bg: <?php echo esc_html($style['button_bg']); ?>;
                --slidepdf-button-icon: <?php echo esc_html($style['button_icon']); ?>;
                --slidepdf-button-radius: <?php echo intval($style['button_radius']); ?>px;
                --slidepdf-slide-radius: <?php echo intval($style['slide_radius']); ?>px;
            }
        </style>
        <div class="slidepdf-container" id="<?php echo esc_attr($id); ?>">
            <div class="slidepdf" data-pdf="<?php echo esc_url($pdf_url); ?>"
                data-swiperconfig="<?php echo esc_attr($swiper_options); ?>">
                <div class="swiper-wrapper">

                </div>

                <div class="controls">
                    <button class="previous navigation">
                        <?php echo $chevron_svg; ?>
                    </button>
                    <button class="next navigation">
                        <?php echo $chevron_svg; ?>
                    </button>
                    <div class="swiper-pagination"></div>
                    <a class="download" href="<?php echo esc_url($pdf_url); ?>" download>Download</a>
                </div>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }

    public static function get_single(string $pdf_url, int $page_number = 1): string
    {
        wp_enqueue_script('pdfjs');
        wp_enqueue_script('slidepdf');
        wp_enqueue_style('slidepdf');

        $id = 'slidepdf-single-' . wp_unique_id();

        ob_start();
        ?>
        <div class="slidepdf single" id="<?php echo esc_attr($id); ?>" data-pdf="<?php echo esc_url($pdf_url); ?>"
            data-single="true" data-page="<?php echo intval($page_number); ?>">
            <canvas></canvas>
            <div class="controls">
                <a class="download" href="<?php echo esc_url($pdf_url); ?>" download>Download</a>
            </div>
        </div>
        <?php

        return ob_get_clean();
    }

}