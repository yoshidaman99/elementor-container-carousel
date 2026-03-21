<?php

namespace Elementor_Container_Carousel\Elementor\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Image_Size;
use Elementor\Repeater;

if (!defined('ABSPATH')) {
    exit;
}

class Slides_Carousel_Widget extends Widget_Base
{
    public function get_name(): string
    {
        return 'ecc_slides_carousel';
    }

    public function get_title(): string
    {
        return __('Slides Carousel', 'elementor-container-carousel');
    }

    public function get_icon(): string
    {
        return 'eicon-carousel';
    }

    public function get_categories(): array
    {
        return ['yosh-tools'];
    }

    public function get_keywords(): array
    {
        return ['carousel', 'slider', 'slides', 'swipe', 'yosh', 'tools'];
    }

    public function get_style_depends(): array
    {
        return ['ecc-carousel'];
    }

    public function get_script_depends(): array
    {
        return ['ecc-carousel'];
    }

    protected function register_controls(): void
    {
        $this->register_slides_content_controls();
        $this->register_carousel_controls();
        $this->register_navigation_controls();
        $this->register_pagination_controls();
        $this->register_autoplay_controls();
        $this->register_effects_controls();
        $this->register_responsive_controls();
        $this->register_performance_controls();
        $this->register_arrow_style_controls();
        $this->register_pagination_style_controls();
        $this->register_slide_style_controls();
    }

    private function register_slides_content_controls(): void
    {
        $this->start_controls_section('slides_content_section', [
            'label' => __('Slides', 'elementor-container-carousel'),
            'tab'   => Controls_Manager::TAB_CONTENT,
        ]);

        $repeater = new Repeater();

        $repeater->add_control('slide_image', [
            'label'     => __('Image', 'elementor-container-carousel'),
            'type'      => Controls_Manager::MEDIA,
            'default'   => [
                'url' => '',
            ],
        ]);

        $repeater->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name'    => 'slide_image_size',
                'default' => 'full',
                'exclude' => ['custom'],
            ]
        );

        $repeater->add_control('slide_title', [
            'label'       => __('Title', 'elementor-container-carousel'),
            'type'        => Controls_Manager::TEXT,
            'default'     => __('Slide Title', 'elementor-container-carousel'),
            'label_block' => true,
        ]);

        $repeater->add_control('slide_description', [
            'label'       => __('Description', 'elementor-container-carousel'),
            'type'        => Controls_Manager::TEXTAREA,
            'default'     => __('Slide description goes here.', 'elementor-container-carousel'),
        ]);

        $repeater->add_control('slide_link', [
            'label'       => __('Link', 'elementor-container-carousel'),
            'type'        => Controls_Manager::URL,
            'placeholder' => __('https://example.com', 'elementor-container-carousel'),
            'dynamic'     => [
                'active' => true,
            ],
        ]);

        $repeater->add_control('slide_content_position', [
            'label'   => __('Content Position', 'elementor-container-carousel'),
            'type'    => Controls_Manager::SELECT,
            'default' => 'bottom-center',
            'options' => [
                'bottom-center' => __('Bottom Center', 'elementor-container-carousel'),
                'bottom-left'   => __('Bottom Left', 'elementor-container-carousel'),
                'bottom-right'  => __('Bottom Right', 'elementor-container-carousel'),
                'center'        => __('Center', 'elementor-container-carousel'),
                'top-center'    => __('Top Center', 'elementor-container-carousel'),
                'top-left'      => __('Top Left', 'elementor-container-carousel'),
                'top-right'     => __('Top Right', 'elementor-container-carousel'),
            ],
        ]);

        $repeater->add_control('slide_overlay', [
            'label'        => __('Enable Overlay', 'elementor-container-carousel'),
            'type'         => Controls_Manager::SWITCHER,
            'label_on'     => __('Yes', 'elementor-container-carousel'),
            'label_off'    => __('No', 'elementor-container-carousel'),
            'return_value' => 'yes',
            'default'      => 'no',
        ]);

        $repeater->add_control('slide_overlay_color', [
            'label'     => __('Overlay Color', 'elementor-container-carousel'),
            'type'      => Controls_Manager::COLOR,
            'default'   => 'rgba(0,0,0,0.4)',
            'condition' => ['slide_overlay' => 'yes'],
        ]);

        $this->add_control('slides_list', [
            'label'       => __('Slides', 'elementor-container-carousel'),
            'type'        => Controls_Manager::REPEATER,
            'fields'      => $repeater->get_controls(),
            'default'     => [
                [
                    'slide_title'       => __('Slide 1', 'elementor-container-carousel'),
                    'slide_description' => __('Click edit button to change this text.', 'elementor-container-carousel'),
                ],
                [
                    'slide_title'       => __('Slide 2', 'elementor-container-carousel'),
                    'slide_description' => __('Click edit button to change this text.', 'elementor-container-carousel'),
                ],
                [
                    'slide_title'       => __('Slide 3', 'elementor-container-carousel'),
                    'slide_description' => __('Click edit button to change this text.', 'elementor-container-carousel'),
                ],
            ],
            'title_field' => '{{{ slide_title }}}',
        ]);

        $this->end_controls_section();
    }

    private function register_carousel_controls(): void
    {
        $this->start_controls_section('carousel_section', [
            'label' => __('Carousel', 'elementor-container-carousel'),
            'tab'   => Controls_Manager::TAB_CONTENT,
        ]);

        $this->add_control('slides_per_view', [
            'label'   => __('Slides Per View', 'elementor-container-carousel'),
            'type'    => Controls_Manager::NUMBER,
            'default' => 1,
            'min'     => 1,
            'max'     => 10,
        ]);

        $this->add_control('space_between', [
            'label'   => __('Space Between (px)', 'elementor-container-carousel'),
            'type'    => Controls_Manager::SLIDER,
            'default' => ['size' => 0],
            'range'   => [
                'px' => ['min' => 0, 'max' => 100, 'step' => 5],
            ],
        ]);

        $this->add_control('centered_slides', [
            'label'        => __('Centered Slides', 'elementor-container-carousel'),
            'type'         => Controls_Manager::SWITCHER,
            'label_on'     => __('Yes', 'elementor-container-carousel'),
            'label_off'    => __('No', 'elementor-container-carousel'),
            'return_value' => 'yes',
            'default'      => 'no',
        ]);

        $this->add_control('loop', [
            'label'        => __('Loop', 'elementor-container-carousel'),
            'type'         => Controls_Manager::SWITCHER,
            'label_on'     => __('Yes', 'elementor-container-carousel'),
            'label_off'    => __('No', 'elementor-container-carousel'),
            'return_value' => 'yes',
            'default'      => 'yes',
        ]);

        $this->add_control('speed', [
            'label'   => __('Transition Speed (ms)', 'elementor-container-carousel'),
            'type'    => Controls_Manager::SLIDER,
            'default' => ['size' => 300],
            'range'   => [
                'px' => ['min' => 100, 'max' => 2000, 'step' => 50],
            ],
        ]);

        $this->add_control('effect', [
            'label'   => __('Effect', 'elementor-container-carousel'),
            'type'    => Controls_Manager::SELECT,
            'default' => 'slide',
            'options' => [
                'slide'     => __('Slide', 'elementor-container-carousel'),
                'fade'      => __('Fade', 'elementor-container-carousel'),
                'cube'      => __('Cube', 'elementor-container-carousel'),
                'coverflow' => __('Coverflow', 'elementor-container-carousel'),
            ],
        ]);

        $this->add_control('grab_cursor', [
            'label'        => __('Grab Cursor', 'elementor-container-carousel'),
            'type'         => Controls_Manager::SWITCHER,
            'label_on'     => __('Yes', 'elementor-container-carousel'),
            'label_off'    => __('No', 'elementor-container-carousel'),
            'return_value' => 'yes',
            'default'      => 'yes',
        ]);

        $this->add_control('keyboard', [
            'label'        => __('Keyboard Control', 'elementor-container-carousel'),
            'type'         => Controls_Manager::SWITCHER,
            'label_on'     => __('Yes', 'elementor-container-carousel'),
            'label_off'    => __('No', 'elementor-container-carousel'),
            'return_value' => 'yes',
            'default'      => 'yes',
        ]);

        $this->end_controls_section();
    }

    private function register_navigation_controls(): void
    {
        $this->start_controls_section('navigation_section', [
            'label' => __('Navigation', 'elementor-container-carousel'),
            'tab'   => Controls_Manager::TAB_CONTENT,
        ]);

        $this->add_control('show_navigation', [
            'label'        => __('Show Arrows', 'elementor-container-carousel'),
            'type'         => Controls_Manager::SWITCHER,
            'label_on'     => __('Yes', 'elementor-container-carousel'),
            'label_off'    => __('No', 'elementor-container-carousel'),
            'return_value' => 'yes',
            'default'      => 'yes',
        ]);

        $this->add_control('nav_prev_icon', [
            'label'     => __('Previous Icon', 'elementor-container-carousel'),
            'type'      => Controls_Manager::ICONS,
            'default'   => [
                'value'   => 'fas fa-chevron-left',
                'library' => 'fa-solid',
            ],
            'condition' => ['show_navigation' => 'yes'],
        ]);

        $this->add_control('nav_next_icon', [
            'label'     => __('Next Icon', 'elementor-container-carousel'),
            'type'      => Controls_Manager::ICONS,
            'default'   => [
                'value'   => 'fas fa-chevron-right',
                'library' => 'fa-solid',
            ],
            'condition' => ['show_navigation' => 'yes'],
        ]);

        $this->end_controls_section();
    }

    private function register_pagination_controls(): void
    {
        $this->start_controls_section('pagination_section', [
            'label' => __('Pagination', 'elementor-container-carousel'),
            'tab'   => Controls_Manager::TAB_CONTENT,
        ]);

        $this->add_control('show_pagination', [
            'label'        => __('Show Pagination', 'elementor-container-carousel'),
            'type'         => Controls_Manager::SWITCHER,
            'label_on'     => __('Yes', 'elementor-container-carousel'),
            'label_off'    => __('No', 'elementor-container-carousel'),
            'return_value' => 'yes',
            'default'      => 'yes',
        ]);

        $this->add_control('pagination_type', [
            'label'     => __('Pagination Type', 'elementor-container-carousel'),
            'type'      => Controls_Manager::SELECT,
            'default'   => 'bullets',
            'options'   => [
                'bullets'     => __('Bullets', 'elementor-container-carousel'),
                'fraction'    => __('Fraction', 'elementor-container-carousel'),
                'progressbar' => __('Progress Bar', 'elementor-container-carousel'),
            ],
            'condition' => ['show_pagination' => 'yes'],
        ]);

        $this->add_control('dynamic_bullets', [
            'label'        => __('Dynamic Bullets', 'elementor-container-carousel'),
            'type'         => Controls_Manager::SWITCHER,
            'label_on'     => __('Yes', 'elementor-container-carousel'),
            'label_off'    => __('No', 'elementor-container-carousel'),
            'return_value' => 'yes',
            'default'      => 'no',
            'condition'    => [
                'show_pagination' => 'yes',
                'pagination_type' => 'bullets',
            ],
        ]);

        $this->add_control('clickable_pagination', [
            'label'        => __('Clickable', 'elementor-container-carousel'),
            'type'         => Controls_Manager::SWITCHER,
            'label_on'     => __('Yes', 'elementor-container-carousel'),
            'label_off'    => __('No', 'elementor-container-carousel'),
            'return_value' => 'yes',
            'default'      => 'yes',
            'condition'    => [
                'show_pagination' => 'yes',
                'pagination_type' => 'bullets',
            ],
        ]);

        $this->end_controls_section();
    }

    private function register_autoplay_controls(): void
    {
        $this->start_controls_section('autoplay_section', [
            'label' => __('Autoplay', 'elementor-container-carousel'),
            'tab'   => Controls_Manager::TAB_CONTENT,
        ]);

        $this->add_control('autoplay', [
            'label'        => __('Autoplay', 'elementor-container-carousel'),
            'type'         => Controls_Manager::SWITCHER,
            'label_on'     => __('Yes', 'elementor-container-carousel'),
            'label_off'    => __('No', 'elementor-container-carousel'),
            'return_value' => 'yes',
            'default'      => 'no',
        ]);

        $this->add_control('autoplay_delay', [
            'label'     => __('Delay (ms)', 'elementor-container-carousel'),
            'type'      => Controls_Manager::SLIDER,
            'default'   => ['size' => 5000],
            'range'     => [
                'px' => ['min' => 500, 'max' => 20000, 'step' => 500],
            ],
            'condition' => ['autoplay' => 'yes'],
        ]);

        $this->add_control('disable_on_interaction', [
            'label'        => __('Stop on Interaction', 'elementor-container-carousel'),
            'type'         => Controls_Manager::SWITCHER,
            'label_on'     => __('Yes', 'elementor-container-carousel'),
            'label_off'    => __('No', 'elementor-container-carousel'),
            'return_value' => 'yes',
            'default'      => 'yes',
            'condition'    => ['autoplay' => 'yes'],
        ]);

        $this->add_control('pause_on_hover', [
            'label'        => __('Pause on Hover', 'elementor-container-carousel'),
            'type'         => Controls_Manager::SWITCHER,
            'label_on'     => __('Yes', 'elementor-container-carousel'),
            'label_off'    => __('No', 'elementor-container-carousel'),
            'return_value' => 'yes',
            'default'      => 'yes',
            'condition'    => ['autoplay' => 'yes'],
        ]);

        $this->end_controls_section();
    }

    private function register_effects_controls(): void
    {
        $this->start_controls_section('effects_section', [
            'label' => __('Effects', 'elementor-container-carousel'),
            'tab'   => Controls_Manager::TAB_CONTENT,
        ]);

        $this->add_control('coverflow_rotate', [
            'label'     => __('Coverflow Rotate', 'elementor-container-carousel'),
            'type'      => Controls_Manager::SLIDER,
            'default'   => ['size' => 50],
            'range'     => [
                'px' => ['min' => 0, 'max' => 90],
            ],
            'condition' => ['effect' => 'coverflow'],
        ]);

        $this->add_control('coverflow_stretch', [
            'label'     => __('Coverflow Stretch', 'elementor-container-carousel'),
            'type'      => Controls_Manager::SLIDER,
            'default'   => ['size' => 0],
            'range'     => [
                'px' => ['min' => 0, 'max' => 200],
            ],
            'condition' => ['effect' => 'coverflow'],
        ]);

        $this->add_control('coverflow_depth', [
            'label'     => __('Coverflow Depth', 'elementor-container-carousel'),
            'type'      => Controls_Manager::SLIDER,
            'default'   => ['size' => 100],
            'range'     => [
                'px' => ['min' => 0, 'max' => 500],
            ],
            'condition' => ['effect' => 'coverflow'],
        ]);

        $this->add_control('cube_shadow', [
            'label'        => __('Cube Shadow', 'elementor-container-carousel'),
            'type'         => Controls_Manager::SWITCHER,
            'label_on'     => __('Yes', 'elementor-container-carousel'),
            'label_off'    => __('No', 'elementor-container-carousel'),
            'return_value' => 'yes',
            'default'      => 'yes',
            'condition'    => ['effect' => 'cube'],
        ]);

        $this->end_controls_section();
    }

    private function register_responsive_controls(): void
    {
        $this->start_controls_section('responsive_section', [
            'label' => __('Responsive', 'elementor-container-carousel'),
            'tab'   => Controls_Manager::TAB_CONTENT,
        ]);

        $this->add_control('slides_per_view_tablet', [
            'label'   => __('Tablet Slides Per View', 'elementor-container-carousel'),
            'type'    => Controls_Manager::NUMBER,
            'default' => 1,
            'min'     => 1,
            'max'     => 10,
        ]);

        $this->add_control('slides_per_view_mobile', [
            'label'   => __('Mobile Slides Per View', 'elementor-container-carousel'),
            'type'    => Controls_Manager::NUMBER,
            'default' => 1,
            'min'     => 1,
            'max'     => 10,
        ]);

        $this->end_controls_section();
    }

    private function register_performance_controls(): void
    {
        $this->start_controls_section('performance_section', [
            'label' => __('Performance', 'elementor-container-carousel'),
            'tab'   => Controls_Manager::TAB_CONTENT,
        ]);

        $this->add_control('lazy_load', [
            'label'        => __('Lazy Load Images', 'elementor-container-carousel'),
            'type'         => Controls_Manager::SWITCHER,
            'label_on'     => __('Yes', 'elementor-container-carousel'),
            'label_off'    => __('No', 'elementor-container-carousel'),
            'return_value' => 'yes',
            'default'      => 'yes',
        ]);

        $this->add_control('observer', [
            'label'        => __('Observer Mode', 'elementor-container-carousel'),
            'type'         => Controls_Manager::SWITCHER,
            'label_on'     => __('Yes', 'elementor-container-carousel'),
            'label_off'    => __('No', 'elementor-container-carousel'),
            'return_value' => 'yes',
            'default'      => 'no',
            'description'  => __('Use MutationObserver for dynamic content changes.', 'elementor-container-carousel'),
        ]);

        $this->end_controls_section();
    }

    private function register_arrow_style_controls(): void
    {
        $this->start_controls_section('arrow_style_section', [
            'label'     => __('Navigation Arrows', 'elementor-container-carousel'),
            'tab'       => Controls_Manager::TAB_STYLE,
            'condition' => ['show_navigation' => 'yes'],
        ]);

        $this->add_control('nav_size', [
            'label'     => __('Arrow Size (px)', 'elementor-container-carousel'),
            'type'      => Controls_Manager::SLIDER,
            'default'   => ['size' => 44],
            'range'     => [
                'px' => ['min' => 24, 'max' => 80],
            ],
            'selectors' => [
                '{{WRAPPER}} .ecc-nav-button' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_control('nav_font_size', [
            'label'     => __('Icon Size (px)', 'elementor-container-carousel'),
            'type'      => Controls_Manager::SLIDER,
            'default'   => ['size' => 18],
            'range'     => [
                'px' => ['min' => 10, 'max' => 40],
            ],
            'selectors' => [
                '{{WRAPPER}} .ecc-nav-button i, {{WRAPPER}} .ecc-nav-button svg' => 'font-size: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->start_controls_tabs('nav_style_tabs');

        $this->start_controls_tab('nav_normal_tab', ['label' => __('Normal', 'elementor-container-carousel')]);

        $this->add_control('nav_color', [
            'label'     => __('Color', 'elementor-container-carousel'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .ecc-nav-button' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_control('nav_bg_color', [
            'label'     => __('Background', 'elementor-container-carousel'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .ecc-nav-button' => 'background-color: {{VALUE}};',
            ],
        ]);

        $this->end_controls_tab();

        $this->start_controls_tab('nav_hover_tab', ['label' => __('Hover', 'elementor-container-carousel')]);

        $this->add_control('nav_color_hover', [
            'label'     => __('Color', 'elementor-container-carousel'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .ecc-nav-button:hover' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_control('nav_bg_color_hover', [
            'label'     => __('Background', 'elementor-container-carousel'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .ecc-nav-button:hover' => 'background-color: {{VALUE}};',
            ],
        ]);

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_control('nav_border_radius', [
            'label'     => __('Border Radius', 'elementor-container-carousel'),
            'type'      => Controls_Manager::SLIDER,
            'default'   => ['size' => 50],
            'range'     => [
                'px' => ['min' => 0, 'max' => 50],
            ],
            'selectors' => [
                '{{WRAPPER}} .ecc-nav-button' => 'border-radius: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_control('nav_position', [
            'label'   => __('Position', 'elementor-container-carousel'),
            'type'    => Controls_Manager::SELECT,
            'default' => 'inside',
            'options' => [
                'inside'  => __('Inside', 'elementor-container-carousel'),
                'outside' => __('Outside', 'elementor-container-carousel'),
            ],
        ]);

        $this->end_controls_section();
    }

    private function register_pagination_style_controls(): void
    {
        $this->start_controls_section('pagination_style_section', [
            'label'     => __('Pagination', 'elementor-container-carousel'),
            'tab'       => Controls_Manager::TAB_STYLE,
            'condition' => ['show_pagination' => 'yes'],
        ]);

        $this->add_control('pagination_position', [
            'label'   => __('Position', 'elementor-container-carousel'),
            'type'    => Controls_Manager::SELECT,
            'default' => 'bottom-center',
            'options' => [
                'bottom-center' => __('Bottom Center', 'elementor-container-carousel'),
                'bottom-left'   => __('Bottom Left', 'elementor-container-carousel'),
                'bottom-right'  => __('Bottom Right', 'elementor-container-carousel'),
            ],
        ]);

        $this->add_control('bullet_size', [
            'label'     => __('Bullet Size (px)', 'elementor-container-carousel'),
            'type'      => Controls_Manager::SLIDER,
            'default'   => ['size' => 10],
            'range'     => [
                'px' => ['min' => 4, 'max' => 20],
            ],
            'selectors' => [
                '{{WRAPPER}} .swiper-pagination-bullet' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
            ],
            'condition' => ['pagination_type' => 'bullets'],
        ]);

        $this->start_controls_tabs('pagination_style_tabs');

        $this->start_controls_tab('pagination_normal_tab', ['label' => __('Normal', 'elementor-container-carousel')]);

        $this->add_control('pagination_color', [
            'label'     => __('Color', 'elementor-container-carousel'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .swiper-pagination-bullet'      => 'background-color: {{VALUE}};',
                '{{WRAPPER}} .swiper-pagination-fraction'    => 'color: {{VALUE}};',
                '{{WRAPPER}} .swiper-pagination-progressbar' => 'background-color: {{VALUE}};',
            ],
        ]);

        $this->end_controls_tab();

        $this->start_controls_tab('pagination_active_tab', ['label' => __('Active', 'elementor-container-carousel')]);

        $this->add_control('pagination_color_active', [
            'label'     => __('Color', 'elementor-container-carousel'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .swiper-pagination-bullet-active'    => 'background-color: {{VALUE}};',
                '{{WRAPPER}} .swiper-pagination-progressbar-fill' => 'background-color: {{VALUE}};',
            ],
        ]);

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_control('progressbar_height', [
            'label'     => __('Progress Bar Height (px)', 'elementor-container-carousel'),
            'type'      => Controls_Manager::SLIDER,
            'default'   => ['size' => 4],
            'range'     => [
                'px' => ['min' => 2, 'max' => 12],
            ],
            'selectors' => [
                '{{WRAPPER}} .swiper-pagination-progressbar' => 'height: {{SIZE}}{{UNIT}};',
            ],
            'condition' => ['pagination_type' => 'progressbar'],
        ]);

        $this->end_controls_section();
    }

    private function register_slide_style_controls(): void
    {
        $this->start_controls_section('slide_style_section', [
            'label' => __('Slide', 'elementor-container-carousel'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('slide_height', [
            'label'     => __('Slide Height', 'elementor-container-carousel'),
            'type'      => Controls_Manager::SLIDER,
            'default'   => ['size' => 400, 'unit' => 'px'],
            'size_units' => ['px', 'vh'],
            'range'     => [
                'px' => ['min' => 100, 'max' => 1000],
                'vh' => ['min' => 20, 'max' => 100],
            ],
            'selectors' => [
                '{{WRAPPER}} .ecc-slide' => 'height: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'slide_title_typography',
                'label'    => __('Title Typography', 'elementor-container-carousel'),
                'selector' => '{{WRAPPER}} .ecc-slide-title',
            ]
        );

        $this->add_control('slide_title_color', [
            'label'     => __('Title Color', 'elementor-container-carousel'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .ecc-slide-title' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'slide_desc_typography',
                'label'    => __('Description Typography', 'elementor-container-carousel'),
                'selector' => '{{WRAPPER}} .ecc-slide-description',
            ]
        );

        $this->add_control('slide_desc_color', [
            'label'     => __('Description Color', 'elementor-container-carousel'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .ecc-slide-description' => 'color: {{VALUE}};',
            ],
        );

        $this->add_responsive_control('slide_content_padding', [
            'label'      => __('Content Padding', 'elementor-container-carousel'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', 'em', '%'],
            'selectors'  => [
                '{{WRAPPER}} .ecc-slide-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);

        $this->add_control('slide_border_radius', [
            'label'     => __('Border Radius', 'elementor-container-carousel'),
            'type'      => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%'],
            'selectors' => [
                '{{WRAPPER}} .ecc-slide, {{WRAPPER}} .ecc-slide img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow: hidden;',
            ],
        ]);

        $this->end_controls_section();
    }

    private function get_swiper_config(): array
    {
        $settings = $this->get_settings_for_display();

        $config = [
            'slidesPerView'  => (int) $settings['slides_per_view'],
            'spaceBetween'   => (int) $settings['space_between']['size'],
            'speed'          => (int) $settings['speed']['size'],
            'grabCursor'     => $settings['grab_cursor'] === 'yes',
            'loop'           => $settings['loop'] === 'yes',
            'centeredSlides' => $settings['centered_slides'] === 'yes',
            'keyboard'       => [
                'enabled'         => $settings['keyboard'] === 'yes',
                'onlyInViewport'  => true,
            ],
            'navigation' => [
                'nextEl' => '.ecc-nav-next-' . $this->get_id(),
                'prevEl' => '.ecc-nav-prev-' . $this->get_id(),
            ],
            'pagination' => [
                'el'             => '.ecc-pagination-' . $this->get_id(),
                'clickable'      => $settings['clickable_pagination'] === 'yes',
                'dynamicBullets' => $settings['dynamic_bullets'] === 'yes',
            ],
            'breakpoints' => [
                767 => [
                    'slidesPerView' => (int) $settings['slides_per_view_mobile'],
                ],
                1024 => [
                    'slidesPerView' => (int) $settings['slides_per_view_tablet'],
                ],
            ],
        ];

        if ($settings['show_navigation'] !== 'yes') {
            unset($config['navigation']);
        }

        if ($settings['show_pagination'] !== 'yes') {
            unset($config['pagination']);
        }

        if ($settings['pagination_type']) {
            $config['pagination']['type'] = $settings['pagination_type'];
        }

        if ($settings['autoplay'] === 'yes') {
            $config['autoplay'] = [
                'delay'                => (int) $settings['autoplay_delay']['size'],
                'disableOnInteraction' => $settings['disable_on_interaction'] === 'yes',
                'pauseOnMouseEnter'    => $settings['pause_on_hover'] === 'yes',
            ];
        }

        if ($settings['effect'] !== 'slide') {
            $config['effect'] = $settings['effect'];

            if ($settings['effect'] === 'coverflow') {
                $config['coverflowEffect'] = [
                    'rotate'       => (int) $settings['coverflow_rotate']['size'],
                    'stretch'      => (int) $settings['coverflow_stretch']['size'],
                    'depth'        => (int) $settings['coverflow_depth']['size'],
                    'modifier'     => 1,
                    'slideShadows' => true,
                ];
            }

            if ($settings['effect'] === 'cube') {
                $config['cubeEffect'] = [
                    'shadow'       => $settings['cube_shadow'] === 'yes',
                    'slideShadows' => true,
                    'shadowOffset' => 20,
                    'shadowScale'  => 0.94,
                ];
            }

            if ($settings['effect'] === 'fade') {
                $config['fadeEffect'] = [
                    'crossFade' => true,
                ];
            }
        }

        if ($settings['lazy_load'] === 'yes') {
            $config['lazy'] = [
                'loadPrevNext'        => true,
                'loadOnTransitionStart' => true,
            ];
        }

        if ($settings['observer'] === 'yes') {
            $config['observer'] = true;
            $config['observeParents'] = true;
        }

        return $config;
    }

    protected function render(): void
    {
        $settings = $this->get_settings_for_display();
        $id = $this->get_id();
        $config = $this->get_swiper_config();

        \Elementor_Container_Carousel\Elementor\Elementor_Integration::enqueue_assets();

        $pagination_class = '';
        if ($settings['show_pagination'] === 'yes' && $settings['pagination_position']) {
            $pagination_class = 'ecc-pagination-' . $settings['pagination_position'];
        }

        $nav_class = '';
        if ($settings['show_navigation'] === 'yes' && $settings['nav_position']) {
            $nav_class = 'ecc-nav-' . $settings['nav_position'];
        }

        $this->add_render_attribute('wrapper', [
            'class'         => ['ecc-swiper-container', 'swiper', $pagination_class, $nav_class],
            'data-swiper'   => wp_json_encode($config),
            'data-widget-id' => $id,
        ]);

        ?>
        <div <?php echo $this->get_render_attribute_string('wrapper'); ?>>
            <div class="swiper-wrapper">
                <?php
                foreach ($settings['slides_list'] as $index => $slide) :
                    $image_url = '';
                    if (!empty($slide['slide_image']['url'])) {
                        $image_url = \Elementor\Group_Control_Image_Size::get_attachment_image_src(
                            $slide['slide_image']['id'],
                            $slide['slide_image_size'],
                            $slide['slide_image']
                        );
                        if (!$image_url) {
                            $image_url = $slide['slide_image']['url'];
                        }
                    }

                    $lazy_class = $settings['lazy_load'] === 'yes' ? 'swiper-lazy' : '';
                    $position_class = $slide['slide_content_position'] ?? 'bottom-center';
                    $overlay_style = '';
                    if (($slide['slide_overlay'] ?? 'no') === 'yes' && !empty($slide['slide_overlay_color'])) {
                        $overlay_style = 'background-color:' . $slide['slide_overlay_color'] . ';';
                    }
                    ?>
                    <div class="swiper-slide ecc-slide">
                        <?php if ($image_url) : ?>
                            <img class="ecc-slide-image <?php echo esc_attr($lazy_class); ?>"
                                 src="<?php echo esc_url($image_url); ?>"
                                 alt="<?php echo esc_attr($slide['slide_title'] ?? ''); ?>"
                                 loading="lazy">
                        <?php endif; ?>
                        <?php if (($slide['slide_overlay'] ?? 'no') === 'yes') : ?>
                            <div class="ecc-slide-overlay" style="<?php echo esc_attr($overlay_style); ?>"></div>
                        <?php endif; ?>
                        <?php if (!empty($slide['slide_title']) || !empty($slide['slide_description'])) : ?>
                            <div class="ecc-slide-content ecc-slide-content-<?php echo esc_attr($position_class); ?>">
                                <?php if (!empty($slide['slide_title'])) : ?>
                                    <<?php echo !empty($slide['slide_link']['url']) ? 'a href="' . esc_url($slide['slide_link']['url']) . '"' : 'span'; ?>
                                        class="ecc-slide-title">
                                        <?php echo esc_html($slide['slide_title']); ?>
                                    </<?php echo !empty($slide['slide_link']['url']) ? 'a' : 'span'; ?>>
                                <?php endif; ?>
                                <?php if (!empty($slide['slide_description'])) : ?>
                                    <div class="ecc-slide-description">
                                        <?php echo wp_kses_post($slide['slide_description']); ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
            <?php if ($settings['show_pagination'] === 'yes') : ?>
                <div class="swiper-pagination ecc-pagination-<?php echo esc_attr($id); ?>"></div>
            <?php endif; ?>
            <?php if ($settings['show_navigation'] === 'yes') : ?>
                <div class="ecc-nav-button ecc-nav-prev-<?php echo esc_attr($id); ?>">
                    <?php \Elementor\Icons_Manager::render_icon($settings['nav_prev_icon'], ['aria-hidden' => 'true']); ?>
                </div>
                <div class="ecc-nav-button ecc-nav-next-<?php echo esc_attr($id); ?>">
                    <?php \Elementor\Icons_Manager::render_icon($settings['nav_next_icon'], ['aria-hidden' => 'true']); ?>
                </div>
            <?php endif; ?>
        </div>
        <?php
    }
}
