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
                'slide_border_color' => '#84848444',
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
        return wp_parse_args(
            get_option(self::OPTION_KEY, []),
            self::defaults()
        );
    }

    /**
     * Merge runtime overrides (Elementor / shortcode)
     * Only non-null values override defaults
     */
    public static function merge(array $overrides): array
    {
        return array_replace_recursive(
            self::get(),
            array_filter($overrides, fn($v) => $v !== null)
        );
    }

    public static function sanitize(array $input): array
    {
        $defaults = self::defaults();

        return [
            'style' => [
                'button_bg' => sanitize_hex_color($input['style']['button_bg'] ?? $defaults['style']['button_bg']),
                'button_icon' => sanitize_hex_color($input['style']['button_icon'] ?? $defaults['style']['button_icon']),
                'button_radius' => absint($input['style']['button_radius'] ?? $defaults['style']['button_radius']),
                'slide_radius' => absint($input['style']['slide_radius'] ?? $defaults['style']['slide_radius']),
            ],
            'swiper' => [
                'slidesPerView' => absint($input['swiper']['slidesPerView'] ?? $defaults['swiper']['slidesPerView']),
                'spaceBetween' => absint($input['swiper']['spaceBetween'] ?? $defaults['swiper']['spaceBetween']),
                'loop' => !empty($input['swiper']['loop']),
            ],
        ];
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
