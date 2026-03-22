<?php

namespace Elementor_Container_Carousel\Elementor\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;

if (!defined('ABSPATH')) {
    exit;
}

class Container_Carousel_Widget extends Widget_Base
{
    public function get_name(): string
    {
        return 'ecc_container_carousel';
    }

    public function get_title(): string
    {
        return __('Container Carousel', 'elementor-container-carousel');
    }

    public function get_icon(): string
    {
        return 'eicon-slider-push';
    }

    public function get_categories(): array
    {
        return ['yosh-tools'];
    }

    public function get_keywords(): array
    {
        return ['carousel', 'slider', 'container', 'swipe', 'yosh', 'tools'];
    }

    public function has_content_wrapper(): bool
    {
        return false;
    }

    public function _content_wrapper_class(): string
    {
        return 'elementor-widget-content';
    }

    public function get_child_type(array $element_data): ?\Elementor\Element_Base
    {
        return \Elementor\Plugin::instance()->elements_manager->get_element_types('container');
    }

    public function getEmptyView(): string
    {
        return __('Drag containers here to create carousel slides.', 'elementor-container-carousel');
    }

    public function get_default_children_elements(): array
    {
        return [
            ['elType' => 'container', 'isInner' => false, 'elements' => []],
            ['elType' => 'container', 'isInner' => false, 'elements' => []],
            ['elType' => 'container', 'isInner' => false, 'elements' => []],
        ];
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
        $this->ecc_register_slides_controls();
        $this->ecc_register_carousel_controls();
        $this->ecc_register_navigation_controls();
        $this->ecc_register_pagination_controls();
        $this->ecc_register_autoplay_controls();
        $this->ecc_register_effects_controls();
        $this->ecc_register_responsive_controls();
        $this->ecc_register_performance_controls();
        $this->ecc_register_arrow_style_controls();
        $this->ecc_register_pagination_style_controls();
    }

    private function ecc_register_slides_controls(): void
    {
        $this->start_controls_section('slides_section', [
            'label' => __('Slides', 'elementor-container-carousel'),
            'tab'   => Controls_Manager::TAB_CONTENT,
        ]);

        $this->add_control('ecc_add_slide', [
            'type'        => Controls_Manager::BUTTON,
            'text'        => __('+ Add Slide', 'elementor-container-carousel'),
            'button_type' => 'default',
            'event'       => 'ecc:addSlide',
            'separator'   => 'after',
        ]);

        $this->add_control('slides_per_group', [
            'label'       => __('Slides Per Group', 'elementor-container-carousel'),
            'type'        => Controls_Manager::NUMBER,
            'default'     => 1,
            'min'         => 1,
            'max'         => 10,
            'step'        => 1,
            'description' => __('Number of slides to group when navigating.', 'elementor-container-carousel'),
        ]);

        $this->add_control('space_between', [
            'label'   => __('Space Between (px)', 'elementor-container-carousel'),
            'type'    => Controls_Manager::SLIDER,
            'default' => ['size' => 20],
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

        $this->add_control('free_mode', [
            'label'        => __('Free Mode', 'elementor-container-carousel'),
            'type'         => Controls_Manager::SWITCHER,
            'label_on'     => __('Yes', 'elementor-container-carousel'),
            'label_off'    => __('No', 'elementor-container-carousel'),
            'return_value' => 'yes',
            'default'      => 'no',
            'description'  => __('Allow slides to move freely without snapping.', 'elementor-container-carousel'),
        ]);

        $this->add_control('grab_cursor', [
            'label'        => __('Grab Cursor', 'elementor-container-carousel'),
            'type'         => Controls_Manager::SWITCHER,
            'label_on'     => __('Yes', 'elementor-container-carousel'),
            'label_off'    => __('No', 'elementor-container-carousel'),
            'return_value' => 'yes',
            'default'      => 'yes',
        ]);

        $this->end_controls_section();
    }

    private function ecc_register_carousel_controls(): void
    {
        $this->start_controls_section('carousel_section', [
            'label' => __('Carousel', 'elementor-container-carousel'),
            'tab'   => Controls_Manager::TAB_CONTENT,
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

        $this->add_control('direction', [
            'label'   => __('Direction', 'elementor-container-carousel'),
            'type'    => Controls_Manager::SELECT,
            'default' => 'horizontal',
            'options' => [
                'horizontal' => __('Horizontal', 'elementor-container-carousel'),
                'vertical'   => __('Vertical', 'elementor-container-carousel'),
            ],
        ]);

        $this->add_control('mousewheel', [
            'label'        => __('Mousewheel Control', 'elementor-container-carousel'),
            'type'         => Controls_Manager::SWITCHER,
            'label_on'     => __('Yes', 'elementor-container-carousel'),
            'label_off'    => __('No', 'elementor-container-carousel'),
            'return_value' => 'yes',
            'default'      => 'no',
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

    private function ecc_register_navigation_controls(): void
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

    private function ecc_register_pagination_controls(): void
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
                'bullets'       => __('Bullets', 'elementor-container-carousel'),
                'fraction'      => __('Fraction', 'elementor-container-carousel'),
                'progressbar'   => __('Progress Bar', 'elementor-container-carousel'),
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

    private function ecc_register_autoplay_controls(): void
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

    private function ecc_register_effects_controls(): void
    {
        $this->start_controls_section('effects_section', [
            'label' => __('Effects', 'elementor-container-carousel'),
            'tab'   => Controls_Manager::TAB_CONTENT,
        ]);

        $this->add_control('effect', [
            'label'   => __('Effect', 'elementor-container-carousel'),
            'type'    => Controls_Manager::SELECT,
            'default' => 'slide',
            'options' => [
                'slide'    => __('Slide', 'elementor-container-carousel'),
                'fade'     => __('Fade', 'elementor-container-carousel'),
                'cube'     => __('Cube', 'elementor-container-carousel'),
                'coverflow' => __('Coverflow', 'elementor-container-carousel'),
            ],
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

    private function ecc_register_responsive_controls(): void
    {
        $this->start_controls_section('responsive_section', [
            'label' => __('Responsive', 'elementor-container-carousel'),
            'tab'   => Controls_Manager::TAB_CONTENT,
        ]);

        $this->add_control('responsive_heading', [
            'label' => __('Desktop', 'elementor-container-carousel'),
            'type'  => Controls_Manager::HEADING,
        ]);

        $this->add_control('slides_per_view_desktop', [
            'label'   => __('Slides Per View', 'elementor-container-carousel'),
            'type'    => Controls_Manager::NUMBER,
            'default' => 1,
            'min'     => 1,
            'max'     => 10,
        ]);

        $this->add_control('tablet_breakpoint', [
            'label'     => __('Tablet Breakpoint (px)', 'elementor-container-carousel'),
            'type'      => Controls_Manager::NUMBER,
            'default'   => 1024,
            'separator' => 'before',
        ]);

        $this->add_control('tablet_heading', [
            'label' => __('Tablet', 'elementor-container-carousel'),
            'type'  => Controls_Manager::HEADING,
        ]);

        $this->add_control('slides_per_view_tablet', [
            'label'   => __('Slides Per View', 'elementor-container-carousel'),
            'type'    => Controls_Manager::NUMBER,
            'default' => 1,
            'min'     => 1,
            'max'     => 10,
        ]);

        $this->add_control('space_between_tablet', [
            'label'   => __('Space Between (px)', 'elementor-container-carousel'),
            'type'    => Controls_Manager::SLIDER,
            'default' => ['size' => 20],
            'range'   => [
                'px' => ['min' => 0, 'max' => 100, 'step' => 5],
            ],
        ]);

        $this->add_control('mobile_breakpoint', [
            'label'     => __('Mobile Breakpoint (px)', 'elementor-container-carousel'),
            'type'      => Controls_Manager::NUMBER,
            'default'   => 767,
            'separator' => 'before',
        ]);

        $this->add_control('mobile_heading', [
            'label' => __('Mobile', 'elementor-container-carousel'),
            'type'  => Controls_Manager::HEADING,
        ]);

        $this->add_control('slides_per_view_mobile', [
            'label'   => __('Slides Per View', 'elementor-container-carousel'),
            'type'    => Controls_Manager::NUMBER,
            'default' => 1,
            'min'     => 1,
            'max'     => 10,
        ]);

        $this->add_control('space_between_mobile', [
            'label'   => __('Space Between (px)', 'elementor-container-carousel'),
            'type'    => Controls_Manager::SLIDER,
            'default' => ['size' => 10],
            'range'   => [
                'px' => ['min' => 0, 'max' => 100, 'step' => 5],
            ],
        ]);

        $this->end_controls_section();
    }

    private function ecc_register_performance_controls(): void
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

        $this->add_control('preload_images', [
            'label'        => __('Preload Images', 'elementor-container-carousel'),
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

    private function ecc_register_arrow_style_controls(): void
    {
        $this->start_controls_section('arrow_style_section', [
            'label'     => __('Navigation Arrows', 'elementor-container-carousel'),
            'tab'       => Controls_Manager::TAB_STYLE,
            'condition' => ['show_navigation' => 'yes'],
        ]);

        $this->add_control('nav_size', [
            'label'      => __('Arrow Size (px)', 'elementor-container-carousel'),
            'type'       => Controls_Manager::SLIDER,
            'default'    => ['size' => 44],
            'range'      => [
                'px' => ['min' => 24, 'max' => 80],
            ],
            'selectors'  => [
                '{{WRAPPER}} .ecc-nav-button' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_control('nav_font_size', [
            'label'      => __('Icon Size (px)', 'elementor-container-carousel'),
            'type'       => Controls_Manager::SLIDER,
            'default'    => ['size' => 18],
            'range'      => [
                'px' => ['min' => 10, 'max' => 40],
            ],
            'selectors'  => [
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
            'label'      => __('Border Radius', 'elementor-container-carousel'),
            'type'       => Controls_Manager::SLIDER,
            'default'    => ['size' => 50],
            'range'      => [
                'px' => ['min' => 0, 'max' => 50],
            ],
            'selectors'  => [
                '{{WRAPPER}} .ecc-nav-button' => 'border-radius: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_control('nav_position', [
            'label'     => __('Position', 'elementor-container-carousel'),
            'type'      => Controls_Manager::SELECT,
            'default'   => 'inside',
            'options'   => [
                'inside'  => __('Inside', 'elementor-container-carousel'),
                'outside' => __('Outside', 'elementor-container-carousel'),
            ],
        ]);

        $this->end_controls_section();
    }

    private function ecc_register_pagination_style_controls(): void
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

        $this->add_control('pagination_spacing', [
            'label'      => __('Spacing (px)', 'elementor-container-carousel'),
            'type'       => Controls_Manager::SLIDER,
            'default'    => ['size' => 10],
            'range'      => [
                'px' => ['min' => 0, 'max' => 30],
            ],
            'selectors'  => [
                '{{WRAPPER}} .swiper-pagination-bullets .swiper-pagination-bullet' => 'margin: 0 {{SIZE}}{{UNIT}};',
            ],
            'condition'  => ['pagination_type' => 'bullets'],
        ]);

        $this->add_control('bullet_size', [
            'label'      => __('Bullet Size (px)', 'elementor-container-carousel'),
            'type'       => Controls_Manager::SLIDER,
            'default'    => ['size' => 10],
            'range'      => [
                'px' => ['min' => 4, 'max' => 20],
            ],
            'selectors'  => [
                '{{WRAPPER}} .swiper-pagination-bullet' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
            ],
            'condition'  => ['pagination_type' => 'bullets'],
        ]);

        $this->start_controls_tabs('pagination_style_tabs');

        $this->start_controls_tab('pagination_normal_tab', ['label' => __('Normal', 'elementor-container-carousel')]);

        $this->add_control('pagination_color', [
            'label'     => __('Color', 'elementor-container-carousel'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .swiper-pagination-bullet'       => 'background-color: {{VALUE}};',
                '{{WRAPPER}} .swiper-pagination-fraction'     => 'color: {{VALUE}};',
                '{{WRAPPER}} .swiper-pagination-progressbar'  => 'background-color: {{VALUE}};',
            ],
        ]);

        $this->end_controls_tab();

        $this->start_controls_tab('pagination_active_tab', ['label' => __('Active', 'elementor-container-carousel')]);

        $this->add_control('pagination_color_active', [
            'label'     => __('Color', 'elementor-container-carousel'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .swiper-pagination-bullet-active'       => 'background-color: {{VALUE}};',
                '{{WRAPPER}} .swiper-pagination-progressbar-fill'    => 'background-color: {{VALUE}};',
            ],
        ]);

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_control('progressbar_height', [
            'label'      => __('Progress Bar Height (px)', 'elementor-container-carousel'),
            'type'       => Controls_Manager::SLIDER,
            'default'    => ['size' => 4],
            'range'      => [
                'px' => ['min' => 2, 'max' => 12],
            ],
            'selectors'  => [
                '{{WRAPPER}} .swiper-pagination-progressbar' => 'height: {{SIZE}}{{UNIT}};',
            ],
            'condition'  => ['pagination_type' => 'progressbar'],
        ]);

        $this->end_controls_section();
    }

    private function get_swiper_config(): array
    {
        $settings = $this->get_settings_for_display();

        $config = [
            'slidesPerView'  => (int) $settings['slides_per_view_desktop'],
            'slidesPerGroup' => (int) $settings['slides_per_group'],
            'spaceBetween'   => (int) $settings['space_between']['size'],
            'speed'          => (int) $settings['speed']['size'],
            'direction'      => $settings['direction'],
            'grabCursor'     => $settings['grab_cursor'] === 'yes',
            'loop'           => $settings['loop'] === 'yes',
            'centeredSlides' => $settings['centered_slides'] === 'yes',
            'freeMode'       => [
                'enabled' => $settings['free_mode'] === 'yes',
            ],
            'mousewheel' => [
                'forceToAxis' => true,
            ],
            'keyboard' => [
                'enabled' => $settings['keyboard'] === 'yes',
                'onlyInViewport' => true,
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
                (int) $settings['mobile_breakpoint'] => [
                    'slidesPerView' => (int) $settings['slides_per_view_mobile'],
                    'spaceBetween'  => (int) $settings['space_between_mobile']['size'],
                ],
                (int) $settings['tablet_breakpoint'] => [
                    'slidesPerView' => (int) $settings['slides_per_view_tablet'],
                    'spaceBetween'  => (int) $settings['space_between_tablet']['size'],
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
                'delay'               => (int) $settings['autoplay_delay']['size'],
                'disableOnInteraction' => $settings['disable_on_interaction'] === 'yes',
                'pauseOnMouseEnter'   => $settings['pause_on_hover'] === 'yes',
            ];
        }

        if ($settings['effect'] !== 'slide') {
            $config['effect'] = $settings['effect'];

            if ($settings['effect'] === 'coverflow') {
                $config['coverflowEffect'] = [
                    'rotate'  => (int) $settings['coverflow_rotate']['size'],
                    'stretch' => (int) $settings['coverflow_stretch']['size'],
                    'depth'   => (int) $settings['coverflow_depth']['size'],
                    'modifier' => 1,
                    'slideShadows' => true,
                ];
            }

            if ($settings['effect'] === 'cube') {
                $config['cubeEffect'] = [
                    'shadow'      => $settings['cube_shadow'] === 'yes',
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

        if ($settings['mousewheel'] !== 'yes') {
            unset($config['mousewheel']);
        }

        if ($settings['lazy_load'] === 'yes') {
            $config['lazy'] = [
                'loadPrevNext' => true,
                'loadOnTransitionStart' => true,
            ];
        }

        if ($settings['preload_images'] === 'yes') {
            $config['preloadImages'] = true;
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
            <div class="swiper-wrapper ecc-slides-wrapper ecc-slide-wrapper">
                <?php
                foreach ($this->get_children() as $child) {
                    $child->add_render_attribute('_wrapper', 'class', 'swiper-slide ecc-slide');
                    $child->print_element();
                }
                ?>
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

    protected function content_template(): void
    {
        ?>
        <div class="ecc-swiper-container swiper" data-swiper="" data-widget-id="{{ view.getID() }}">
            <div class="swiper-wrapper ecc-slides-wrapper ecc-slide-wrapper">
                <slot></slot>
            </div>
            <# if (settings.show_pagination === 'yes') { #>
                <div class="swiper-pagination ecc-pagination-{{ view.getID() }}"></div>
            <# } #>
            <# if (settings.show_navigation === 'yes') { #>
                <div class="ecc-nav-button ecc-nav-prev-{{ view.getID() }}">
                    <# if (settings.nav_prev_icon && settings.nav_prev_icon.value) { #>
                        <i class="{{ settings.nav_prev_icon.value }}"></i>
                    <# } #>
                </div>
                <div class="ecc-nav-button ecc-nav-next-{{ view.getID() }}">
                    <# if (settings.nav_next_icon && settings.nav_next_icon.value) { #>
                        <i class="{{ settings.nav_next_icon.value }}"></i>
                    <# } #>
                </div>
            <# } #>
        </div>
        <?php
    }
}
