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
        return ['slidepdf', 'swiper', 'swiper-pagination'];
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
        $config = \SlidePDF\Config::defaults();

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
                'default' => 'yes',
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

        foreach ($config as $sectionName => $items) {

            $this->start_controls_section(
                'section_' . $sectionName,
                [
                    'label' => ucfirst($sectionName),
                    'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                ]
            );

            foreach ($items as $item) {

                $control_id = $item->id;
                $control_label = $item->name;
                $type = $item->inputType();


                switch ($type) {
                    case 'color':
                        $this->add_control(
                            $control_id,
                            [
                                'label' => $control_label,
                                'type' => \Elementor\Controls_Manager::COLOR,
                                'selectors' => [
                                    "{{WRAPPER}} .slidepdf-container" => '--slidepdf-' . $item->id . ': {{VALUE}} !important;',
                                ],
                            ]
                        );
                        break;

                    case 'number':
                        $this->add_responsive_control(
                            $control_id,
                            [
                                'label' => $control_label,
                                'type' => \Elementor\Controls_Manager::SLIDER,
                                'size_units' => ['px', 'em', '%', 'rem'],
                                'default' => [
                                    'size' => $item->value,
                                    'unit' => $item->unit ?: 'px',
                                ],
                                'selectors' => [
                                    '{{WRAPPER}} .slidepdf-container' => '--slidepdf-' . $item->id . ': {{SIZE}}{{UNIT}} !important;',
                                ],
                            ]
                        );
                        break;

                    case 'checkbox':
                        $this->add_control(
                            $control_id,
                            [
                                'label' => $control_label,
                                'type' => \Elementor\Controls_Manager::SWITCHER,
                                'return_value' => 'yes',
                                'default' => $item->value ? 'yes' : '',
                                'selectors' => [
                                    "{{WRAPPER}} .slidepdf-container" => '--slidepdf-' . $item->id . ': {{VALUE}} !important;',
                                ],
                            ]
                        );
                        break;

                    case 'text':
                    default:
                        $this->add_control(
                            $control_id,
                            [
                                'label' => $control_label,
                                'type' => \Elementor\Controls_Manager::TEXT,
                                'default' => $item->value,
                                'selectors' => [
                                    "{{WRAPPER}} .slidepdf-container" => '--slidepdf-' . $item->id . ': {{VALUE}} !important;',
                                ],
                            ]
                        );
                        break;
                }
            }

            $this->end_controls_section();
        }
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

        $config = \SlidePDF\Config::get();

        $config['swiper'] = array_replace($config['swiper'], [
            'slidesPerView' => isset($settings['slidesPerView']) ? (int) $settings['slidesPerView'] : 1,
            'spaceBetween' => isset($settings['spaceBetween']) ? (int) $settings['spaceBetween'] : 0,
            'loop' => isset($settings['loop']) && $settings['loop'] === 'yes',
        ]);


        $id = 'slidepdf-' . $this->get_id();
        echo UI::get_slider($pdf_url, $config, $id);
    }



}