<?php

namespace Elementor_Container_Carousel\Elementor;

if (!defined('ABSPATH')) {
    exit;
}

class Elementor_Integration
{
    public function register_widgets($widgets_manager): void
    {
        error_log('[ECC] Elementor: ' . (defined('ELEMENTOR_VERSION') ? ELEMENTOR_VERSION : 'not loaded'));

        $possible_classes = [
            'Elementor\Widget_Container',
            'Elementor\Element_Container',
            'Elementor\Elements\Container',
            'Elementor\Core\Elements\Container',
            'Elementor\Modules\Container\Container',
            'Elementor\Includes\Elements\Container',
        ];
        foreach ($possible_classes as $cls) {
            if (class_exists($cls, true)) {
                error_log('[ECC] FOUND: ' . $cls);
                $parent = $cls;
                while ($parent = get_parent_class($parent)) {
                    error_log('[ECC]   extends: ' . $parent);
                }
                $ref = new \ReflectionClass($cls);
                $methods = array_map(function ($m) { return $m->getName(); }, $ref->getMethods(\ReflectionMethod::IS_PUBLIC | \ReflectionMethod::IS_ABSTRACT));
                error_log('[ECC]   public methods: ' . implode(', ', $methods));
            }
        }

        error_log('[ECC] Widget_Container class NOT found - listing Elementor\\ classes with "container" in name');
        foreach (get_declared_classes() as $cls) {
            if (stripos($cls, 'Elementor') !== false && stripos($cls, 'container') !== false) {
                error_log('[ECC]   ' . $cls);
            }
        }

        require_once ECC_DIR . 'src/Elementor/Widgets/Slides_Carousel_Widget.php';
        $widgets_manager->register(new Widgets\Slides_Carousel_Widget());
    }

    public static function register_styles(): void
    {
        wp_register_style(
            'ecc-swiper',
            ECC_URL . 'assets/css/swiper-bundle.min.css',
            [],
            ECC_VERSION
        );

        wp_register_style(
            'ecc-carousel',
            ECC_URL . 'assets/css/carousel.min.css',
            ['ecc-swiper'],
            ECC_VERSION
        );
    }

    public static function register_scripts(): void
    {
        wp_register_script(
            'ecc-swiper',
            ECC_URL . 'assets/js/swiper-bundle.min.js',
            [],
            ECC_VERSION,
            true
        );

        wp_register_script(
            'ecc-carousel',
            ECC_URL . 'assets/js/carousel.min.js',
            ['ecc-swiper'],
            ECC_VERSION,
            true
        );
    }

    public static function enqueue_assets(): void
    {
        wp_enqueue_style('ecc-carousel');
        wp_enqueue_script('ecc-carousel');
    }
}
