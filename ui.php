<?php
namespace SlidePDF;

class UI
{
    /**
     * @param string $pdf_url
     * @param array $options
     */


    public static function get_slider(string $pdf_url, array $options = []): string
    {
        wp_enqueue_script('pdfjs');
        wp_enqueue_script('slidepdf');
        wp_enqueue_script('swiper');
        wp_enqueue_style('slidepdf');
        wp_enqueue_style('swiper');

        $json_options = esc_attr(wp_json_encode($options));

        ob_start();
        ?>
        <div class="slidepdf-container">
            <div class="slidepdf" data-pdf="<?php echo esc_url($pdf_url); ?>"
                data-options="<?php echo esc_attr($json_options); ?>">
                <div class="swiper-wrapper">

                </div>

                <div class="controls">
                    <div class="swiper-button-prev"></div>
                    <div class="swiper-button-next"></div>
                    <div class="swiper-pagination"></div>
                    <a class="download" href="<?php echo esc_url($pdf_url); ?>" download>Download</a>
                </div>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }
}