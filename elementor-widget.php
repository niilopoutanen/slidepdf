<?php
namespace SlidePDF;


class Elementor_Widget extends \Elementor\Widget_Base
{

    public function get_name(): string
    {
        return 'slidepdf';
    }

    public function get_title(): string
    {
        return esc_html__('SlidePDF', 'slidepdf');
    }

    public function get_icon(): string
    {
        return 'eicon-document-file';
    }

    public function get_categories(): array
    {
        return ['general'];
    }

    public function get_keywords(): array
    {
        return ['pdf', 'slider', 'document', 'viewer'];
    }

    public function get_script_depends(): array
    {
        return ['pdfjs', 'slidepdf', 'swiper'];
    }

    public function get_style_depends(): array
    {
        return ['slidepdf', 'swiper'];
    }

    public function has_widget_inner_wrapper(): bool
    {
        return true;
    }

    protected function is_dynamic_content(): bool
    {
        return false;
    }

    protected function register_controls(): void
    {
        $this->start_controls_section(
            'section_content',
            [
                'label' => esc_html__('Content', 'slidepdf'),
            ]
        );

        $this->add_control(
            'use_media_library',
            [
                'label' => esc_html__('Use Media Library?', 'slidepdf'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'slidepdf'),
                'label_off' => esc_html__('No', 'slidepdf'),
                'return_value' => 'yes',
                'default' => '',
            ]
        );

        $this->add_control(
            'pdf_url',
            [
                'label' => esc_html__('PDF URL', 'slidepdf'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'input_type' => 'url',
                'placeholder' => esc_html__('https://example.com/sample.pdf', 'slidepdf'),
                'default' => '',
                'condition' => [
                    'use_media_library!' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'pdf_file',
            [
                'label' => esc_html__('Select PDF', 'slidepdf'),
                'type' => \Elementor\Controls_Manager::MEDIA,
                'media_types' => ['application/pdf'],
                'dynamic' => [
                    'active' => true,
                ],
                'condition' => [
                    'use_media_library' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style',
            [
                'label' => esc_html__('Style', 'slidepdf'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );


        $this->add_control(
            'slides_per_view',
            [
                'label' => esc_html__('Slides per page', 'slidepdf'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 10,
                'step' => 1,
                'default' => 1,
            ]
        );

        $this->add_control(
            'space_between',
            [
                'label' => esc_html__('Space between slides', 'slidepdf'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 0,
                'step' => 1,
                'default' => 10,
            ]
        );

        $this->add_control(
            'loop',
            [
                'label' => esc_html__('Loop slides', 'slidepdf'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'slidepdf'),
                'label_off' => esc_html__('No', 'slidepdf'),
                'return_value' => 'yes',
                'default' => '',
            ]
        );
        $this->end_controls_section();

    }


    protected function render(): void
    {
        $settings = $this->get_settings_for_display();

        $pdf_url = '';
        if (!empty($settings['use_media_library']) && $settings['use_media_library'] === 'yes') {
            $pdf_url = $settings['pdf_file']['url'] ?? '';
        } else {
            $pdf_url = $settings['pdf_url'] ?? '';
        }

        if (!$pdf_url) {
            return;
        }

        // Build ONLY Elementor overrides
        $overrides = [
            'swiper' => [
                'slidesPerView' => isset($settings['slides_per_view'])
                    ? (int) $settings['slides_per_view']
                    : null,

                'spaceBetween' => isset($settings['space_between'])
                    ? (int) $settings['space_between']
                    : null,

                'loop' => isset($settings['loop'])
                    ? $settings['loop'] === 'yes'
                    : null,
            ],
        ];

        // Merge with global config
        $config = \SlidePDF\Config::merge($overrides);

        echo wp_kses_post(
            UI::get_slider(
                $pdf_url,
                $config
            )
        );
    }



}