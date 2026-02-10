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
        $id = 'slidepdf-' . wp_unique_id();

        $stored = Config::get();
        $config = array_replace_recursive($stored, $config);

        $chevron_svg = file_get_contents(
            plugin_dir_path(__FILE__) . 'assets/chevron.svg'
        );


        ob_start();
        ?>
        <?php echo '<style>#' . $id . '{' . Config::toCss($config) . '}</style>'; ?>
        <div class="slidepdf-container" id="<?php echo esc_attr($id); ?>">
            <div class="slidepdf" data-pdf="<?php echo esc_url($pdf_url); ?>"
                data-swiperconfig="<?php echo esc_attr(wp_json_encode($config['swiper'])); ?>">
                <div class="swiper-wrapper">

                </div>
                <?php if (($config['features']['show_controls'] ?? true) !== false): ?>
                    <div class="controls">
                        <button class="previous navigation"><?php echo $chevron_svg; ?></button>
                        <button class="next navigation"><?php echo $chevron_svg; ?></button>

                        <?php if (($config['features']['show_pagination'] ?? true) !== false): ?>
                            <div class="swiper-pagination"></div>
                        <?php endif; ?>

                        <?php if (($config['features']['show_download'] ?? true) !== false): ?>
                            <a class="download" href="<?php echo esc_url($pdf_url); ?>" download>Download</a>
                        <?php endif; ?>

                    </div>
                <?php endif; ?>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }

    public static function get_single(string $pdf_url, int $page_number = 1): string
    {
        $id = 'slidepdf-single-' . wp_unique_id();
        $config = Config::get();
        ob_start();
        ?>
        <?php echo '<style>#' . $id . '{' . Config::toCss($config) . '}</style>'; ?>

        <div class="slidepdf single" id="<?php echo esc_attr($id); ?>" data-pdf="<?php echo esc_url($pdf_url); ?>"
            data-single="true" data-page="<?php echo intval($page_number); ?>">
            <div class="page">
                <canvas></canvas>
            </div>
            <?php if (($config['features']['show_controls'] ?? true) !== false): ?>
            <div class="controls">
                <?php if (($config['features']['show_download'] ?? true) !== false): ?>
                    <a class="download" href="<?php echo esc_url($pdf_url); ?>" download>Download</a>
                <?php endif; ?>
            </div>
            <?php endif; ?>
        </div>
        <?php

        return ob_get_clean();
    }




}