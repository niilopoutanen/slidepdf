<?php
namespace PDF_Slider;

class UI
{
    public static function get_slider(string $pdf_url): string
    {
        wp_enqueue_script('pdfjs');
        wp_enqueue_script('pdf-slider');
        wp_enqueue_script('swiper');
        wp_enqueue_style('pdf-slider');
        wp_enqueue_style('swiper');

        wp_enqueue_style('swiper-pagination');

        ob_start();
        ?>
        <div class="pdf-slider-container">
            <div class="pdf-slider" data-pdf="<?php echo esc_url($pdf_url); ?>">
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