<?php

namespace Elementor_Container_Carousel\Core;

if (!defined('ABSPATH')) {
    exit;
}

class Plugin
{
    private static ?self $instance = null;

    private function __construct()
    {
        $this->init_hooks();
    }

    public static function get_instance(): self
    {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function init_hooks(): void
    {
        add_action('init', [$this, 'load_textdomain']);

        add_action('elementor/widgets/register', [$this, 'register_widgets']);
        add_action('elementor/elements/categories_registered', [$this, 'register_widget_category']);
        add_action('elementor/frontend/after_register_styles', [\Elementor_Container_Carousel\Elementor\Elementor_Integration::class, 'register_styles']);
        add_action('elementor/frontend/after_register_scripts', [\Elementor_Container_Carousel\Elementor\Elementor_Integration::class, 'register_scripts']);
    }

    public function load_textdomain(): void
    {
        load_plugin_textdomain(
            'elementor-container-carousel',
            false,
            dirname(ECC_BASENAME) . '/languages'
        );
    }

    public function register_widget_category($elements_manager): void
    {
        $elements_manager->add_category('yosh-tools', [
            'title' => __('Yosh Tools', 'elementor-container-carousel'),
            'icon'  => 'fa fa-plug',
        ]);
    }

    public function register_widgets($widgets_manager): void
    {
        if (!class_exists('\Elementor\Widget_Base')) {
            return;
        }

        $integration = new \Elementor_Container_Carousel\Elementor\Elementor_Integration();
        $integration->register_widgets($widgets_manager);
    }
}
