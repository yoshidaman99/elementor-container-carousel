# Elementor Container Carousel

Transform any Elementor Container into a responsive carousel powered by Swiper.js.

## Features

- **Container Wrapper Widget** - Drag any Elementor containers inside to make them carousel slides
- **Slides Carousel Widget** - Add slides via repeater controls with image, title, description, and link
- **Multiple Effects** - Slide, Fade, Cube, and Coverflow transitions
- **Navigation** - Customizable prev/next arrows with Elementor icon picker
- **Pagination** - Bullets, Fraction, and Progress Bar options
- **Autoplay** - Configurable delay, pause on hover, stop on interaction
- **Responsive** - Independent slides-per-view settings for desktop, tablet, and mobile
- **Performance Optimized** - Lazy loading, Intersection Observer, no jQuery dependency, GPU-accelerated
- **Full Styling Controls** - Colors, typography, spacing, border radius for arrows, pagination, and slides
- **Loop Mode** - Infinite scrolling
- **Centered Slides** - Active slide centered with configurable offset
- **Free Mode** - Slides move freely without snapping
- **Keyboard & Mousewheel** - Accessibility-friendly navigation
- **RTL Support** - Full right-to-left language support via Swiper

## Requirements

- WordPress 5.8 or higher
- PHP 7.4 or higher
- Elementor 3.0 or higher

## Installation

1. Download the plugin ZIP
2. Go to **WordPress Admin > Plugins > Add New > Upload Plugin**
3. Activate the plugin
4. Edit a page with Elementor
5. Find the widgets under **Yosh Tools** category

## Widgets

### Container Carousel
Drag this widget onto your page, then drag any Elementor containers/widgets inside it. Each direct child becomes a carousel slide.

### Slides Carousel
Use the repeater controls to add slides with images, titles, descriptions, and optional links. Includes overlay options and content positioning.

## Performance

This plugin is optimized for fast loading:

- **No jQuery** - Pure vanilla JavaScript
- **Conditional Loading** - Assets only load on pages using the widget
- **Footer Scripts** - JS loads in footer, non-blocking
- **GPU Accelerated** - CSS transforms for smooth animations
- **CSS Containment** - Rendering containment for faster paint
- **Lazy Loading** - Images load only when visible
- **Native lazy attribute** - `loading="lazy"` on all images
- **~15KB gzipped JS** total footprint

## Changelog

### 1.0.0
- Initial release
- Container Carousel widget (wraps Elementor containers)
- Slides Carousel widget (repeater-based)
- Swiper.js 11 integration
- Slide, Fade, Cube, Coverflow effects
- Navigation arrows with icon picker
- Bullets, Fraction, Progress Bar pagination
- Autoplay with configurable delay
- Responsive breakpoints
- Lazy loading for images
- Full Elementor style controls
- RTL support
- Yosh Tools widget category

## License

GPL v2 or later

## Author

**Jerel Yoshida**
- GitHub: [@yoshidaman99](https://github.com/yoshidaman99)
