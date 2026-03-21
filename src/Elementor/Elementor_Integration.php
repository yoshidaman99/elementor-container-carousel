<?php

namespace Elementor_Container_Carousel\Elementor;

if (!defined('ABSPATH')) {
    exit;
}

class Elementor_Integration
{
    public function register_widgets($widgets_manager): void
    {
        error_log('[ECC] Registering widgets - Elementor: ' . (defined('ELEMENTOR_VERSION') ? ELEMENTOR_VERSION : 'not loaded'));

        if (class_exists('\Elementor\Widget_Container', true)) {
            error_log('[ECC] Widget_Container class found');
            try {
                require_once ECC_DIR . 'src/Elementor/Widgets/Container_Carousel_Widget.php';
                $widget = new Widgets\Container_Carousel_Widget();
                $widgets_manager->register($widget);
                error_log('[ECC] Container_Carousel_Widget registered successfully');
            } catch (\Throwable $e) {
                error_log('[ECC] Container_Carousel_Widget FAILED: ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine());
            }
        } else {
            error_log('[ECC] Widget_Container class NOT found');
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
