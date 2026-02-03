<?php
namespace PDF_Slider;


class Elementor_Widget extends \Elementor\Widget_Base
{

    public function get_name(): string
    {
        return 'pdf-slider';
    }

    public function get_title(): string
    {
        return esc_html__('PDF Slider', 'pdf-slider');
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
        return ['pdfjs', 'pdf-slider', 'swiper'];
    }

    public function get_style_depends(): array
    {
        return ['pdf-slider', 'swiper'];
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
                'label' => esc_html__('Content', 'pdf-slider'),
            ]
        );

        $this->add_control(
            'pdf_url',
            [
                'label' => esc_html__('PDF URL', 'pdf-slider'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'input_type' => 'url',
                'placeholder' => esc_html__('https://example.com/sample.pdf', 'pdf-slider'),
                'default' => '',
            ]
        );

        $this->end_controls_section();
    }

    protected function render(): void
    {
        $settings = $this->get_settings_for_display();

        echo UI::get_slider($settings["pdf_url"]);
    }

}