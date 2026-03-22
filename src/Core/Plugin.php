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

        add_action('elementor/init', [$this, 'register_widget_category'], 5);
        add_action('elementor/widgets/register', [$this, 'register_widgets']);
        add_action('elementor/editor/after_enqueue_scripts', [\Elementor_Container_Carousel\Elementor\Elementor_Integration::class, 'enqueue_editor_assets']);
        add_action('elementor/frontend/after_register_styles', [\Elementor_Container_Carousel\Elementor\Elementor_Integration::class, 'register_styles']);
        add_action('elementor/frontend/after_register_scripts', [\Elementor_Container_Carousel\Elementor\Elementor_Integration::class, 'register_scripts']);

        add_filter('get_post_metadata', [$this, 'filter_elementor_data'], 10, 4);
    }

    public function load_textdomain(): void
    {
        load_plugin_textdomain(
            'elementor-container-carousel',
            false,
            dirname(ECC_BASENAME) . '/languages'
        );
    }

    public function register_widget_category(): void
    {
        $elements_manager = \Elementor\Plugin::instance()->elements_manager;

        if (!$elements_manager || !method_exists($elements_manager, 'add_category')) {
            return;
        }

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

    private static array $sanitized_cache = [];
    private static bool $filtering = false;

    public function filter_elementor_data($check, $object_id, $meta_key, $single)
    {
        if ($meta_key !== '_elementor_data' || is_admin() || wp_doing_ajax() || self::$filtering) {
            return $check;
        }

        if (isset(self::$sanitized_cache[$object_id])) {
            $data = self::$sanitized_cache[$object_id];
            return $single ? $data : [$data];
        }

        self::$filtering = true;
        remove_filter('get_post_metadata', [$this, 'filter_elementor_data']);

        $raw_data = get_post_meta($object_id, $meta_key, true);

        add_filter('get_post_metadata', [$this, 'filter_elementor_data'], 10, 4);
        self::$filtering = false;

        if (!is_array($raw_data)) {
            return $check;
        }

        $cleaned = $this->sanitize_elements($raw_data);
        self::$sanitized_cache[$object_id] = $cleaned;

        return $single ? $cleaned : [$cleaned];
    }

    private function sanitize_elements(array $elements): array
    {
        $result = [];

        foreach ($elements as $element) {
            if (isset($element['elType']) && $element['elType'] === 'widget' && empty($element['widgetType'])) {
                continue;
            }

            if (isset($element['elements']) && is_array($element['elements'])) {
                $element['elements'] = $this->sanitize_elements($element['elements']);
            }

            $result[] = $element;
        }

        return $result;
    }
}
