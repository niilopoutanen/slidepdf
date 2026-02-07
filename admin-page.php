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
            margin-top: 10px;
            cursor: pointer;
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
        <code class="shortcode" onclick="copyShortcode()">[slidepdf src="https://example.com/file.pdf"]</code>
        <script>
            function copyShortcode() {
                navigator.clipboard.writeText('[slidepdf src="https://example.com/file.pdf"]')
            }
        </script>
    </div>

    <div class="section style">
        <h2>Style settings</h2>
        <form method="post" action="options.php">
            <?php settings_fields('slidepdf_settings'); ?>

            <p>Button background color</p>
            <input type="color" name="slidepdf_config[style][button_bg]"
                value="<?php echo esc_attr($options['style']['button_bg']); ?>">

            <p>Button icon color</p>
            <input type="color" name="slidepdf_config[style][button_icon]"
                value="<?php echo esc_attr($options['style']['button_icon']); ?>">

            <p>Button border radius (px)</p>
            <input type="number" min="0" name="slidepdf_config[style][button_radius]"
                value="<?php echo esc_attr($options['style']['button_radius']); ?>">

            <p>Slide border radius (px)</p>
            <input type="number" min="0" name="slidepdf_config[style][slide_radius]"
                value="<?php echo esc_attr($options['style']['slide_radius']); ?>">

            <?php submit_button(); ?>
        </form>

    </div>


    <div class="section swiper">
        <h2>Swiper settings</h2>
        <form method="post" action="options.php">
            <?php settings_fields('slidepdf_settings'); ?>


            <p>Slides per view</p>
            <input type="number" min="1" max="10" name="slidepdf_config[swiper][slidesPerView]"
                value="<?php echo esc_attr($options['swiper']['slidesPerView']); ?>">

            <?php submit_button(); ?>
        </form>
    </div>

    <?php
}