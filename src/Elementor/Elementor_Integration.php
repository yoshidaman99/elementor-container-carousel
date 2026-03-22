<?php

namespace Elementor_Container_Carousel\Elementor;

if (!defined('ABSPATH')) {
    exit;
}

class Elementor_Integration
{
    public function register_widgets($widgets_manager): void
    {
        require_once ECC_DIR . 'src/Elementor/Widgets/Slides_Carousel_Widget.php';
        require_once ECC_DIR . 'src/Elementor/Widgets/Container_Carousel_Widget.php';

        $widgets_manager->register(new Widgets\Slides_Carousel_Widget());
        $widgets_manager->register(new Widgets\Container_Carousel_Widget());
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

    public static function enqueue_editor_assets(): void
    {
        wp_enqueue_script(
            'ecc-carousel-editor',
            ECC_URL . 'assets/js/editor.js',
            [],
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
