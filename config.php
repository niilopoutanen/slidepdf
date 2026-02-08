<?php
namespace SlidePDF;

final class Config
{
    public const OPTION_KEY = 'slidepdf_config';

    public static function defaults(): array
    {
        return [
            'style' => [
                'width' => '100%',
                'height' => '100%',

                'button_bg' => '#e6e6e6',
                'button_icon' => '#121212',
                'button_radius' => 1000,
                'button_size' => 40,
                'button_border_width' => 0,
                'button_border_color' => 'transparent',
                'button_hover_bg' => '#dcdcdc',
                'button_hover_icon' => '#000000',

                'slide_radius' => 20,
                'slide_border_width' => 2,
                'slide_border_color' => '#e3e3e3',
                'slide_bg' => '#ffffff',

                'pagination_color' => '#121212',
                'pagination_active' => '#000000',
                'pagination_size' => 8,

                'controls_gap' => 10,
                'controls_opacity' => 1,
            ],

            'swiper' => [
                'slidesPerView' => 1,
                'spaceBetween' => 10,
                'loop' => false,
                'speed' => 300,
                'centeredSlides' => false,
                'autoHeight' => true,
            ],

            'show_controls' => true,
            'show_pagination' => true,
            'show_download' => true,
        ];
    }


    public static function get(): array
    {
        $saved = get_option('slidepdf_config', []);

        return self::merge(self::defaults(), $saved);
    }


    private static function merge(array $defaults, array $saved): array
    {
        foreach ($saved as $key => $value) {
            if (is_array($value) && isset($defaults[$key]) && is_array($defaults[$key])) {
                $defaults[$key] = self::merge($defaults[$key], $value);
            } else {
                $defaults[$key] = $value;
            }
        }

        return $defaults;
    }


    public static function sanitize(array $input): array
    {
        $defaults = self::defaults();
        $saved = get_option(self::OPTION_KEY, []);

        $clean = self::merge($defaults, $saved);
        $clean = self::merge($clean, $input);

        $clean['style']['button_bg'] = sanitize_hex_color($clean['style']['button_bg']);
        $clean['style']['button_icon'] = sanitize_hex_color($clean['style']['button_icon']);
        $clean['style']['width'] = sanitize_text_field($clean['style']['width']);
        $clean['style']['height'] = sanitize_text_field($clean['style']['height']);

        $clean['swiper']['slidesPerView'] = absint($clean['swiper']['slidesPerView']);
        $clean['swiper']['loop'] = !empty($clean['swiper']['loop']);

        $clean['show_controls'] = !empty($input['show_controls']);
        $clean['show_pagination'] = !empty($input['show_pagination']);
        $clean['show_download'] = !empty($input['show_download']);


        return $clean;
    }


    public static function register(): void
    {
        register_setting(
            'slidepdf_settings',
            self::OPTION_KEY,
            [
                'type' => 'array',
                'sanitize_callback' => [self::class, 'sanitize'],
                'default' => self::defaults(),
            ]
        );
    }
}
