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
            'section_style_layout',
            [
                'label' => esc_html__('Layout', 'slidepdf'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'style_width',
            [
                'label' => esc_html__('Width', 'slidepdf'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => '100%',
            ]
        );

        $this->add_control(
            'style_height',
            [
                'label' => esc_html__('Height', 'slidepdf'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => '100%',
            ]
        );

        $this->end_controls_section();
        $this->start_controls_section(
            'section_style_slides',
            [
                'label' => esc_html__('Slides', 'slidepdf'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'slide_bg',
            [
                'label' => esc_html__('Background', 'slidepdf'),
                'type' => \Elementor\Controls_Manager::COLOR,
            ]
        );

        $this->add_control(
            'slide_radius',
            [
                'label' => esc_html__('Border radius (px)', 'slidepdf'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 0,
            ]
        );

        $this->add_control(
            'slide_border_width',
            [
                'label' => esc_html__('Border width (px)', 'slidepdf'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 0,
            ]
        );

        $this->add_control(
            'slide_border_color',
            [
                'label' => esc_html__('Border color', 'slidepdf'),
                'type' => \Elementor\Controls_Manager::COLOR,
            ]
        );


        $this->end_controls_section();


        $this->start_controls_section(
            'section_style_pagination',
            [
                'label' => esc_html__('Pagination', 'slidepdf'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'pagination_color',
            [
                'label' => esc_html__('Color', 'slidepdf'),
                'type' => \Elementor\Controls_Manager::COLOR,
            ]
        );

        $this->add_control(
            'pagination_active',
            [
                'label' => esc_html__('Active color', 'slidepdf'),
                'type' => \Elementor\Controls_Manager::COLOR,
            ]
        );

        $this->add_control(
            'pagination_size',
            [
                'label' => esc_html__('Size (px)', 'slidepdf'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 4,
            ]
        );

        $this->end_controls_section();


        $this->start_controls_section(
            'section_swiper',
            [
                'label' => esc_html__('Slider', 'slidepdf'),
            ]
        );

        $this->add_control(
            'slides_per_view',
            [
                'label' => esc_html__('Slides per view', 'slidepdf'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 1,
            ]
        );

        $this->add_control(
            'space_between',
            [
                'label' => esc_html__('Space between slides (px)', 'slidepdf'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 0,
            ]
        );

        $this->add_control(
            'loop',
            [
                'label' => esc_html__('Loop', 'slidepdf'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
            ]
        );

        $this->end_controls_section();


    }


    protected function render(): void
    {
        $settings = $this->get_settings_for_display();

        $pdf_url = !empty($settings['pdf_file']['url'])
            ? $settings['pdf_file']['url']
            : $settings['pdf_url'];

        if (!$pdf_url) {
            return;
        }

        echo UI::get_slider($pdf_url, [
            'swiper' => [
                'slidesPerView' => (int) $settings['slides_per_view'],
                'spaceBetween' => (int) $settings['space_between'],
                'loop' => $settings['loop'] === 'yes',
            ],
        ]);
    }



}