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
        wp_enqueue_style('swiper-pagination');

        $id = 'slidepdf-' . wp_unique_id();

        $swiper_options = esc_attr(wp_json_encode($config['swiper']));

        $chevron_svg = file_get_contents(
            plugin_dir_path(__FILE__) . 'assets/chevron.svg'
        );


        ob_start();
        ?>
        <?php echo UI::get_css($id, $config); ?>
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
        <?php echo UI::get_css($id, Config::get()); ?>

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


    private static function get_css(string $target_id, array $config): void
    {
        $style = $config['style'] ?? [];
        ?>
        <style>
            #<?php echo esc_attr($target_id); ?> {
                --slidepdf-width: <?php echo esc_html($style['width']); ?>;
                --slidepdf-height: <?php echo esc_html($style['height']); ?>;

                --slidepdf-button-bg: <?php echo esc_html($style['button_bg']); ?>;
                --slidepdf-button-icon: <?php echo esc_html($style['button_icon']); ?>;
                --slidepdf-button-hover-bg: <?php echo esc_html($style['button_hover_bg']); ?>;
                --slidepdf-button-hover-icon: <?php echo esc_html($style['button_hover_icon']); ?>;
                --slidepdf-button-size: <?php echo intval($style['button_size']); ?>px;
                --slidepdf-button-radius: <?php echo intval($style['button_radius']); ?>px;
                --slidepdf-button-border-width: <?php echo intval($style['button_border_width']); ?>px;
                --slidepdf-button-border-color: <?php echo esc_html($style['button_border_color']); ?>;

                --slidepdf-slide-bg: <?php echo esc_html($style['slide_bg']); ?>;
                --slidepdf-slide-radius: <?php echo intval($style['slide_radius']); ?>px;
                --slidepdf-slide-border-width: <?php echo intval($style['slide_border_width']); ?>px;
                --slidepdf-slide-border-color: <?php echo esc_html($style['slide_border_color']); ?>;
                --slidepdf-slide-shadow: <?php echo esc_html($style['slide_shadow']); ?>;

                --slidepdf-pagination-color: <?php echo esc_html($style['pagination_color']); ?>;
                --slidepdf-pagination-active: <?php echo esc_html($style['pagination_active']); ?>;
                --slidepdf-pagination-size: <?php echo intval($style['pagination_size']); ?>px;

                --slidepdf-controls-gap: <?php echo intval($style['controls_gap']); ?>px;
                --slidepdf-controls-opacity: <?php echo floatval($style['controls_opacity']); ?>;
            }
        </style>
        <?php
    }


}