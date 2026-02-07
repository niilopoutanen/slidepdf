<?php
namespace SlidePDF;
use SlidePDF\Config;

add_action('admin_menu', function () {
    add_management_page('SlidePDF', 'SlidePDF', 'install_plugins', 'slidepdf', '\SlidePDF\render_page', '');
});

function render_page()
{
    $options = Config::get();

    ?>
    <style>
        .section.header {
            gap: 40px;
            display: flex;
            padding-bottom: 0;
            flex-direction: row;
            align-items: center;
        }

        .header .icon {
            width: 200px;
            height: 200px;
            object-fit: contain;
        }

        .header .content {
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .header h1 {
            font-size: 50px;
            font-weight: 600;
            margin: 0;
        }

        .header p {
            font-size: 20px;
        }

        .section {
            padding: 50px;
            box-sizing: border-box;
            padding-bottom: 0;

            display: flex;
            flex-direction: column;
            align-items: start;
        }

        .shortcode {
            background-color: #ffffff;
            border-radius: 10px;
            border: 2px solid #dadada;
            padding: 15px;
            cursor: pointer;
        }

        .label {
            margin-top: 10px;
            margin-bottom: 5px;
        }
    </style>
    <div class="header section">
        <img class="icon" src="<?php echo plugin_dir_url(__FILE__); ?>assets/icon.svg" />
        <div class="content">
            <h1>SlidePDF</h1>
            <p>Simple way to embed PDFs on your website</p>
        </div>

    </div>

    <div class="section guide">
        <h2>Usage</h2>
        <span class="label">Load in a slider</span>
        <code class="shortcode" onclick="copyShortcode(this)">[slidepdf src="https://example.com/file.pdf"]</code>

        <span class="label">Load only a single page</span>
        <code class="shortcode" onclick="copyShortcode(this)">[slidepdf src="https://example.com/file.pdf" page="2"]</code>

        <script>
            function copyShortcode(code) {
                navigator.clipboard.writeText(code.textContent);
            }

        </script>
    </div>

    <div class="section style">
        <h2>Style settings</h2>

        <form method="post" action="options.php">
            <?php settings_fields('slidepdf_settings'); ?>

            <h3>Layout</h3>

            <p>Width</p>
            <input type="text" name="slidepdf_config[style][width]"
                value="<?php echo esc_attr($options['style']['width']); ?>">

            <p>Height</p>
            <input type="text" name="slidepdf_config[style][height]"
                value="<?php echo esc_attr($options['style']['height']); ?>">

            <h3>Buttons</h3>

            <p>Button background</p>
            <input type="color" name="slidepdf_config[style][button_bg]"
                value="<?php echo esc_attr($options['style']['button_bg']); ?>">

            <p>Button icon color</p>
            <input type="color" name="slidepdf_config[style][button_icon]"
                value="<?php echo esc_attr($options['style']['button_icon']); ?>">

            <p>Button hover background</p>
            <input type="color" name="slidepdf_config[style][button_hover_bg]"
                value="<?php echo esc_attr($options['style']['button_hover_bg']); ?>">

            <p>Button hover icon</p>
            <input type="color" name="slidepdf_config[style][button_hover_icon]"
                value="<?php echo esc_attr($options['style']['button_hover_icon']); ?>">

            <p>Button size (px)</p>
            <input type="number" min="0" name="slidepdf_config[style][button_size]"
                value="<?php echo esc_attr($options['style']['button_size']); ?>">

            <p>Button border radius (px)</p>
            <input type="number" min="0" name="slidepdf_config[style][button_radius]"
                value="<?php echo esc_attr($options['style']['button_radius']); ?>">

            <p>Button border width (px)</p>
            <input type="number" min="0" name="slidepdf_config[style][button_border_width]"
                value="<?php echo esc_attr($options['style']['button_border_width']); ?>">

            <p>Button border color</p>
            <input type="color" name="slidepdf_config[style][button_border_color]"
                value="<?php echo esc_attr($options['style']['button_border_color']); ?>">

            <h3>Slides</h3>

            <p>Slide background</p>
            <input type="color" name="slidepdf_config[style][slide_bg]"
                value="<?php echo esc_attr($options['style']['slide_bg']); ?>">

            <p>Slide border radius (px)</p>
            <input type="number" min="0" name="slidepdf_config[style][slide_radius]"
                value="<?php echo esc_attr($options['style']['slide_radius']); ?>">

            <p>Slide border width (px)</p>
            <input type="number" min="0" name="slidepdf_config[style][slide_border_width]"
                value="<?php echo esc_attr($options['style']['slide_border_width']); ?>">

            <p>Slide border color</p>
            <input type="color" name="slidepdf_config[style][slide_border_color]"
                value="<?php echo esc_attr($options['style']['slide_border_color']); ?>">

            <p>Slide shadow (CSS)</p>
            <input type="text" name="slidepdf_config[style][slide_shadow]"
                value="<?php echo esc_attr($options['style']['slide_shadow']); ?>">

            <h3>Controls</h3>

            <p>Controls gap (px)</p>
            <input type="number" min="0" name="slidepdf_config[style][controls_gap]"
                value="<?php echo esc_attr($options['style']['controls_gap']); ?>">

            <p>Controls opacity</p>
            <input type="number" min="0" max="1" step="0.1" name="slidepdf_config[style][controls_opacity]"
                value="<?php echo esc_attr($options['style']['controls_opacity']); ?>">

            <h3>Pagination</h3>

            <p>Pagination color</p>
            <input type="color" name="slidepdf_config[style][pagination_color]"
                value="<?php echo esc_attr($options['style']['pagination_color']); ?>">

            <p>Active pagination color</p>
            <input type="color" name="slidepdf_config[style][pagination_active]"
                value="<?php echo esc_attr($options['style']['pagination_active']); ?>">

            <p>Pagination size (px)</p>
            <input type="number" min="1" name="slidepdf_config[style][pagination_size]"
                value="<?php echo esc_attr($options['style']['pagination_size']); ?>">

            <?php submit_button(); ?>
        </form>
    </div>



    <div class="section swiper">
        <h2>Swiper settings</h2>

        <form method="post" action="options.php">
            <?php settings_fields('slidepdf_settings'); ?>

            <p>Slides per view</p>
            <input type="number" min="1" max="10"
                name="slidepdf_config[swiper][slidesPerView]"
                value="<?php echo esc_attr($options['swiper']['slidesPerView']); ?>">

            <p>Space between slides (px)</p>
            <input type="number" min="0"
                name="slidepdf_config[swiper][spaceBetween]"
                value="<?php echo esc_attr($options['swiper']['spaceBetween']); ?>">

            <p>Transition speed (ms)</p>
            <input type="number" min="0"
                name="slidepdf_config[swiper][speed]"
                value="<?php echo esc_attr($options['swiper']['speed']); ?>">

            <p>
                <label>
                    <input type="checkbox"
                        name="slidepdf_config[swiper][loop]"
                        value="1"
                        <?php checked($options['swiper']['loop']); ?>>
                    Loop slides
                </label>
            </p>

            <p>
                <label>
                    <input type="checkbox"
                        name="slidepdf_config[swiper][centeredSlides]"
                        value="1"
                        <?php checked($options['swiper']['centeredSlides']); ?>>
                    Center slides
                </label>
            </p>

            <p>
                <label>
                    <input type="checkbox"
                        name="slidepdf_config[swiper][autoHeight]"
                        value="1"
                        <?php checked($options['swiper']['autoHeight']); ?>>
                    Auto height
                </label>
            </p>

            <?php submit_button(); ?>
        </form>
    </div>

    <div class="section features">
        <h2>Features</h2>

        <form method="post" action="options.php">
            <?php settings_fields('slidepdf_settings'); ?>

            <p>
                <label>
                    <input type="checkbox"
                        name="slidepdf_config[show_controls]"
                        value="1"
                        <?php checked($options['show_controls']); ?>>
                    Show controls
                </label>
            </p>

            <p>
                <label>
                    <input type="checkbox"
                        name="slidepdf_config[show_pagination]"
                        value="1"
                        <?php checked($options['show_pagination']); ?>>
                    Show pagination
                </label>
            </p>

            <p>
                <label>
                    <input type="checkbox"
                        name="slidepdf_config[show_download]"
                        value="1"
                        <?php checked($options['show_download']); ?>>
                    Show download button
                </label>
            </p>

            <?php submit_button(); ?>
        </form>
    </div>



    <?php
}