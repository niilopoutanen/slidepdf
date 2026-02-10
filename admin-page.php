<?php
namespace SlidePDF;
use SlidePDF\Config;

add_action("admin_menu", function () {
    add_management_page(
        "SlidePDF",
        "SlidePDF",
        "install_plugins",
        "slidepdf",
        '\SlidePDF\render_page'
    );
});

function render_page()
{
    if (!empty($_POST['slidepdf_reset']) && check_admin_referer('slidepdf_reset_defaults')) {
        delete_option(Config::OPTION_KEY);
        $config = Config::defaults();
        echo '<div class="updated notice"><p>Settings have been reset to defaults.</p></div>';
    }

    $config = Config::getItems(); ?>

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
        .control{
            display: flex;
            flex-wrap: wrap;
            gap: 5px;
            align-items: center;
        }
        
        .control label{
            width: 100%;
        }
        .control input{
            background-color: #ffffff;
            border: 2px solid #dadada;
            border-radius: 10px;
        }

        .control input[type="text"]{
            padding: 5px 10px;
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
        <img class="icon" src="<?php echo plugin_dir_url(__FILE__) . 'assets/icon.svg'; ?>" />
        <div class="content">
            <h1>SlidePDF</h1>
            <p>Simple way to embed PDFs on your website</p>
        </div>
    </div>

    <div class="section guide">
        <h2>Usage</h2>
        <span class="label">Load in a slider</span>
        <code class="shortcode" onclick="copyShortcode(this)">
                    [slidepdf src="https://example.com/file.pdf"]
                </code>

        <span class="label">Load only a single page</span>
        <code class="shortcode" onclick="copyShortcode(this)">
                    [slidepdf src="https://example.com/file.pdf" page="2"]
                </code>

        <script>
            function copyShortcode(code) {
                navigator.clipboard.writeText(code.textContent);
            }
        </script>
    </div>

    <form method="post" action="options.php">
        <?php settings_fields(Config::OPTION_KEY); ?>

        <?php foreach ($config as $sectionName => $items): ?>
            <div class="section <?php echo esc_attr($sectionName); ?>">
                <h2><?php echo esc_html(ucfirst($sectionName)); ?></h2>

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
                        <label class="label" for="<?php echo $id; ?>">
                            <?php echo $label; ?>
                        </label>

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

        <?php submit_button(__('Save Settings', 'slidepdf')); ?>
    </form>

    <form method="post" action="">
        <?php wp_nonce_field('slidepdf_reset_defaults'); ?>
        <input type="hidden" name="slidepdf_reset" value="1">
        <?php submit_button('Reset to Defaults', 'secondary'); ?>
    </form>


    <?php
}
