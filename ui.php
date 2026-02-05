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

        $chevron_svg = file_get_contents(
            plugin_dir_path(__FILE__) . 'assets/chevron.svg'
        );


        ob_start();
        ?>
        <div class="slidepdf-container">
            <div class="slidepdf" data-pdf="<?php echo esc_url($pdf_url); ?>"
                data-options="<?php echo esc_attr($json_options); ?>">
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
}