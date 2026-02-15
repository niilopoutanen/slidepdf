<?php
namespace SlidePDF;
use SlidePDF\Config;

if (!defined('ABSPATH')) {
    exit;
}

add_action('admin_menu', function () {
    add_management_page(
        esc_html__('SlidePDF', 'slidepdf'),
        esc_html__('SlidePDF', 'slidepdf'),
        'install_plugins',
        'slidepdf',
        '\SlidePDF\render_page'
    );
});

function render_page()
{
    if (!empty($_POST['slidepdf_reset']) && check_admin_referer('slidepdf_reset_defaults')) {
        delete_option(Config::OPTION_KEY);
        $config = Config::defaults();
        echo '<div class="updated notice"><p>' . esc_html__('Settings have been reset to defaults.', 'slidepdf') . '</p></div>';
    }

    $base_url = plugin_dir_url(__FILE__);
    $config = Config::getItems();

    $section_labels = [
        'style' => esc_html__('Style', 'slidepdf'),
        'swiper' => esc_html__('Swiper', 'slidepdf'),
        'features' => esc_html__('Features', 'slidepdf'),
    ];

    ?>

    <div class="slidepdf settings">
        <div class="header section">
            <img class="icon" src="<?php echo esc_url($base_url . 'static/icon.png'); ?>" />
            <div class="content">
                <h1><?php echo esc_html__('SlidePDF', 'slidepdf'); ?></h1>
                <p><?php echo esc_html__('Simple way to embed PDFs on your website', 'slidepdf'); ?></p>
                <div class="links">
                    <a href="https://github.com/niilopoutanen/slidepdf" target="_blank" rel="noopener noreferrer">
                        <img class="banner" src="<?php echo esc_url($base_url . 'static/github-banner.svg'); ?>" />
                    </a>
                    <a href="https://wordpress.org/plugins/slidepdf/" target="_blank" rel="noopener noreferrer">
                        <img class="banner" src="<?php echo esc_url($base_url . 'static/wp-banner.svg'); ?>" />
                    </a>
                </div>
            </div>
        </div>

        <div class="section guide bg">
            <h2><?php echo esc_html__('Usage', 'slidepdf'); ?></h2>
            <span class="label"><?php echo esc_html__('Load in a slider', 'slidepdf'); ?></span>
            <code class="shortcode" onclick="copyShortcode(this)">
                        [slidepdf src="https://example.com/file.pdf"]'
                    </code>

            <span class="label"><?php echo esc_html__('Load only a single page', 'slidepdf'); ?></span>
            <code class="shortcode" onclick="copyShortcode(this)">
                        [slidepdf src="https://example.com/file.pdf" page="2"]
                    </code>

            <script>
                function copyShortcode(code) {
                    const text = code.textContent.trim();
                    if (navigator.clipboard && window.isSecureContext) {
                        navigator.clipboard.writeText(text);
                    } else {
                        const textarea = document.createElement("textarea");
                        textarea.value = text;
                        textarea.style.position = "fixed";
                        textarea.style.opacity = "0";
                        document.body.appendChild(textarea);
                        textarea.focus();
                        textarea.select();
                        document.execCommand("copy");
                        document.body.removeChild(textarea);
                    }
                }
            </script>

            <p><?php echo esc_html__("If your website uses caching, please clear the cache to see your changes after updating settings below (for example: Elementor, LiteSpeed, W3 Total Cache, etc.)", "slidepdf"); ?>
            </p>
        </div>

        <form method="post" action="options.php">
            <?php settings_fields(Config::OPTION_KEY); ?>

            <?php foreach ($config as $sectionName => $items): ?>
                <div class="section bg <?php echo esc_attr($sectionName); ?>">
                    <h2>
                        <?php
                        echo $section_labels[$sectionName] ?? esc_html(ucfirst($sectionName));
                        ?>
                    </h2>

                    <?php foreach ($items as $item): ?>
                        <?php
                        $type = $item->inputType();
                        $attrs = $item->inputAttributes();
                        $value = $type === 'checkbox' ? 1 : $item->value;
                        $checked = $type === 'checkbox' && $item->value ? 'checked' : '';
                        $label = esc_html($item->name);
                        $id = esc_attr($sectionName . '_' . $item->id);
                        $name = esc_attr("slidepdf_config[{$sectionName}][{$item->id}]");
                        ?>

                        <div class="control">
                            <label class="label" for="<?php echo $id; ?>"><?php echo $label; ?></label>

                            <?php if ($type === 'checkbox'): ?>
                                <input type="checkbox" id="<?php echo $id; ?>" name="<?php echo $name; ?>" value="1" <?php echo $checked; ?>>
                            <?php else: ?>
                                <input type="<?php echo esc_attr($type); ?>" id="<?php echo $id; ?>" name="<?php echo $name; ?>"
                                    value="<?php echo esc_attr($value); ?>" <?php echo $attrs; ?>>
                                <?php if ($item->unit): ?>
                                    <span><?php echo esc_html($item->unit); ?></span>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>

                    <?php endforeach; ?>
                </div>
            <?php endforeach; ?>

            <div class="section submitbuttons">
                <?php submit_button(esc_html__('Save Settings', 'slidepdf')); ?>
            </div>
        </form>

        <div class="section submitbuttons">
            <form method="post" action="">
                <?php wp_nonce_field('slidepdf_reset_defaults'); ?>
                <input type="hidden" name="slidepdf_reset" value="1">
                <?php submit_button(esc_html__('Reset to Defaults', 'slidepdf'), 'secondary'); ?>
            </form>
        </div>

    </div>

    <?php
}
