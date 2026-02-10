<?php
namespace SlidePDF;
final class ConfigItem
{
    public string $id;
    public string $name;
    public $value;
    public ?string $unit;

    public function __construct(string $id, string $name, $value, ?string $unit = null)
    {
        $this->id = $id;
        $this->name = $name;
        $this->value = $value;
        $this->unit = $unit;
    }

    public function toCss(): string
    {
        return $this->unit ? "{$this->value}{$this->unit}" : (string) $this->value;
    }

    public function inputType(): string
    {
        $idLower = strtolower($this->id);

        if (
            strpos($idLower, 'color') !== false ||
            strpos($idLower, 'bg') !== false ||
            (is_string($this->value) && preg_match('/^#(?:[0-9a-fA-F]{3}){1,2}$/', $this->value))
        ) {
            return 'color';
        }

        if (is_numeric($this->value) && $this->unit === 'px') {
            return 'number';
        }

        if (is_bool($this->value)) {
            return 'checkbox';
        }

        return 'text';
    }


    public function inputAttributes(): string
    {
        if ($this->inputType() === 'number') {
            return 'step="1" min="0"';
        }

        return '';
    }
}

final class Config
{
    public const OPTION_KEY = 'slidepdf_config';

    /** @return array<string, ConfigItem[]> */
    public static function defaults(): array
    {
        return [
            'style' => [
                new ConfigItem('width', __('Width', 'slidepdf'), '100%'),
                new ConfigItem('height', __('Height', 'slidepdf'), '100%'),

                new ConfigItem('button_bg', __('Button background color', 'slidepdf'), '#e6e6e6'),
                new ConfigItem('button_icon', __('Button foreground color', 'slidepdf'), '#121212'),
                new ConfigItem('button_radius', __('Button border radius', 'slidepdf'), 1000, 'px'),
                new ConfigItem('button_size', __('Button size', 'slidepdf'), 40, 'px'),
                new ConfigItem('button_border_width', __('Button border width', 'slidepdf'), 0, 'px'),
                new ConfigItem('button_border_color', __('Button border color', 'slidepdf'), 'transparent'),
                new ConfigItem('button_hover_bg', __('Button background color on hover', 'slidepdf'), '#dcdcdc'),
                new ConfigItem('button_hover_icon', __('Button foreground color on hover', 'slidepdf'), '#000000'),

                new ConfigItem('slide_radius', __('Slide border radius', 'slidepdf'), 20, 'px'),
                new ConfigItem('slide_border_width', __('Slide border width', 'slidepdf'), 2, 'px'),
                new ConfigItem('slide_border_color', __('Slide border color', 'slidepdf'), '#e3e3e3'),
                new ConfigItem('slide_bg', __('Slide background color', 'slidepdf'), '#ffffff'),

                new ConfigItem('pagination_color', __('Pagination color', 'slidepdf'), '#e6e6e6'),
                new ConfigItem('pagination_active', __('Active pagination color', 'slidepdf'), '#2c3aff'),
                new ConfigItem('pagination_size', __('Pagination size', 'slidepdf'), 8, 'px'),

                new ConfigItem('controls_gap', __('Controls gap', 'slidepdf'), 10, 'px'),
                new ConfigItem('controls_opacity', __('Controls opacity', 'slidepdf'), 1),
            ],

            'swiper' => [
                new ConfigItem('slidesPerView', __('Slides per view', 'slidepdf'), 1),
                new ConfigItem('spaceBetween', __('Space between slides', 'slidepdf'), 10),
                new ConfigItem('loop', __('Loop slides', 'slidepdf'), false),
                new ConfigItem('speed', __('Transition speed', 'slidepdf'), 300),
                new ConfigItem('centeredSlides', __('Centered slides', 'slidepdf'), false),
                new ConfigItem('autoHeight', __('Auto height', 'slidepdf'), true),
            ],

            'features' => [
                new ConfigItem('show_controls', __('Show controls', 'slidepdf'), true),
                new ConfigItem('show_pagination', __('Show pagination', 'slidepdf'), true),
                new ConfigItem('show_download', __('Show download button', 'slidepdf'), true),
            ],
        ];

    }

    public static function getItems(): array
    {
        $stored = get_option(self::OPTION_KEY, []);
        $defaults = self::defaults();

        foreach ($defaults as $section => $items) {
            foreach ($items as $item) {
                if (isset($stored[$section][$item->id])) {
                    $item->value = $stored[$section][$item->id];
                }
            }
        }

        return $defaults;
    }

    public static function get(): array
    {
        $stored = get_option(self::OPTION_KEY, []);
        $defaults = self::defaults();
        $values = [];

        foreach ($defaults as $section => $items) {
            foreach ($items as $item) {
                $values[$section][$item->id] =
                    $stored[$section][$item->id] ?? $item->value;
            }
        }

        return $values;
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

    public static function toCss(array $config): string
    {
        $lines = [];
        $defaults = self::defaults();

        foreach ($defaults['style'] as $item) {
            $value = $config['style'][$item->id] ?? $item->value;
            $lines[] = "--slidepdf-{$item->id}: "
                    . ($item->unit ? "{$value}{$item->unit}" : $value)
                    . ";";
        }

        return implode("\n", $lines);
    }

    public static function sanitize(array $input): array
    {
        $defaults = self::defaults();
        $clean = [];

        foreach ($defaults as $section => $items) {
            foreach ($items as $item) {
                $id = $item->id;
                $default = $item->value;

                if (!isset($input[$section][$id])) {
                    if (is_bool($default)) {
                        $clean[$section][$id] = false;
                    }
                    continue;
                }

                $value = $input[$section][$id];

                if (is_bool($default)) {
                    $clean[$section][$id] = (bool) $value;
                } elseif (is_int($default)) {
                    $clean[$section][$id] = (int) $value;
                } elseif (is_float($default)) {
                    $clean[$section][$id] = (float) $value;
                } else {
                    $clean[$section][$id] = sanitize_text_field($value);
                }
            }
        }

        return $clean;
    }



    public static function register(): void
    {
        register_setting(
            Config::OPTION_KEY,
            self::OPTION_KEY,
            [
                'type' => 'array',
                'sanitize_callback' => [self::class, 'sanitize'],
                'default' => self::defaults(),
            ]
        );
    }
}



