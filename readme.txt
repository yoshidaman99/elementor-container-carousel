=== Elementor Container Carousel ===
Contributors: jerel-yoshida
Tags: elementor, carousel, slider, swiper, container, slides, responsive, yosh-tools
Requires at least: 5.8
Tested up to: 6.7
Requires PHP: 7.4
Stable tag: 1.1.27
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Transform any Elementor Container into a responsive carousel with Swiper.js.

== Description ==

Elementor Container Carousel adds two powerful carousel widgets to Elementor: a Container Wrapper that turns any Elementor containers into slides, and a Slides Carousel with a repeater for image-based slides. Powered by Swiper.js with full performance optimization.

= Features =

* **Container Wrapper Widget** - Drag containers inside to create slides
* **Slides Carousel Widget** - Repeater-based slides with image, title, description
* **Multiple Effects** - Slide, Fade, Cube, Coverflow transitions
* **Navigation Arrows** - Customizable with Elementor icon picker
* **Pagination** - Bullets, Fraction, Progress Bar
* **Autoplay** - Configurable delay, pause on hover
* **Responsive** - Desktop, tablet, mobile breakpoints
* **Performance** - No jQuery, lazy loading, GPU-accelerated, ~15KB gzipped
* **RTL Support** - Full right-to-left language support
* **Loop Mode** - Infinite scrolling
* **Keyboard & Mousewheel** - Accessible navigation

== Installation ==

1. Upload the plugin files to `/wp-content/plugins/elementor-container-carousel/` or install through WordPress plugins screen.
2. Activate the plugin.
3. Edit a page with Elementor.
4. Find **Container Carousel** or **Slides Carousel** under the **Yosh Tools** widget category.

== Frequently Asked Questions ==

= How do I use the Container Carousel? =

Add the Container Carousel widget to your page, then drag any Elementor containers or widgets inside it. Each direct child element becomes a carousel slide.

= How do I use the Slides Carousel? =

Add the Slides Carousel widget, then use the repeater in the Content tab to add slides with images, titles, descriptions, and optional links.

= Is this fast? =

Yes. The plugin uses vanilla JavaScript (no jQuery), conditional asset loading, lazy image loading, GPU-accelerated CSS animations, and loads scripts in the footer. Total JS footprint is ~15KB gzipped.

= Does it work with Elementor Container (Flexbox)? =

Yes, the Container Carousel widget is specifically designed to wrap Elementor containers.

== Changelog ==

= 1.1.17 =
* Fix JS-side getEmptyView by patching elementor.elementsManager.getElementTypeClass for our widget types
* Previous prototype patches targeted the wrong object — getEmptyView is called on element type classes, not Widget views

= 1.1.16 =
* Fix JS-side getEmptyView ForceMethodImplementation error by patching prototype before preview renders
* Replace delayed setTimeout patch with immediate polling that runs during elementor.init

= 1.1.15 =
* Fix "can't start a section before end of previous section" error in Elementor 3.35+ editor
* Add content_template() to Slides Carousel widget for proper JS-based editor rendering
* Add getEmptyView() to both widgets for Elementor 3.35+ compatibility
* Remove deprecated has_content_wrapper() and _content_wrapper_class() methods

= 1.1.14 =
* Fix ForceMethodImplementation error for Widget.getEmptyView() in Elementor 3.35+ editor
* Patch JS Widget view prototype to provide getEmptyView for our widget type
* Remove unused PHP getEmptyView method (not wired to JS)

= 1.1.13 =
* Fix ForceMethodImplementation error for Widget.getEmptyView() in Elementor 3.35+
* Implement getEmptyView() with drag-and-drop placeholder message

= 1.1.12 =
* Replace Backbone collection hack with temporary method patching for Add Slide
* Patch isValidChild and getChildType on widget instance, run $e.run, then restore
* Add Slide now uses Elementor's official command pipeline for proper canvas rendering

= 1.1.11 =
* Fix Add Slide button silently failing due to Widget isValidChild restriction
* Use Backbone elements collection directly instead of $e.run for adding slides
* Fix editor styles targeting wrong CSS selectors for slide containers
* Move editor script enqueue to after_enqueue_scripts for proper load order
* Add elementor-editor as script dependency

= 1.1.10 =
* Replace inline editor slide controls (badges, delete buttons) with panel "Add Slide" button
* Simplify editor.js by removing preview iframe DOM manipulation
* Add editor styles for minimum carousel height in preview

= 1.1.9 =
* Fix editor controls targeting preview iframe instead of admin document
* Improve editor slide management reliability

= 1.0.0 =
* Initial release
* Container Carousel widget (wraps Elementor containers as slides)
* Slides Carousel widget (repeater-based with image/title/description)
* Swiper.js 11 integration
* Slide, Fade, Cube, Coverflow effects
* Navigation arrows with custom icons
* Bullets, Fraction, Progress Bar pagination
* Autoplay with delay, pause on hover, stop on interaction
* Responsive breakpoints (desktop/tablet/mobile)
* Lazy loading for images
* Full Elementor style controls
* RTL support
* Yosh Tools widget category

== Upgrade Notice ==

= 1.0.0 =
Initial release of Elementor Container Carousel plugin.
