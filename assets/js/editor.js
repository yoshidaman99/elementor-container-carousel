(function () {
    'use strict';

    if (typeof elementor === 'undefined') {
        return;
    }

    var WIDGET_TYPE = 'ecc_container_carousel';

    // Fired when the "+ Add Slide" button in the panel is clicked.
    // In Elementor 3.x+, view.options.container is the Elementor Container object directly.
    // Fallback: look it up by element ID from view.options.model.
    elementor.channels.editor.on('ecc:addSlide', function (view) {
        var container = view && view.options && view.options.container;

        if (!container) {
            var elementId = view && view.options && view.options.model && view.options.model.get('id');
            if (!elementId || typeof elementor.getContainer !== 'function') {
                return;
            }
            container = elementor.getContainer(elementId);
        }

        if (!container) {
            return;
        }

        $e.run('document/elements/create', {
            container: container,
            model: {
                elType: 'container',
                settings: {},
            },
        });
    });

    // Inject editor-only styles into the preview iframe once it is ready.
    function injectEditorStyles() {
        var previewDoc = getPreviewDocument();
        if (!previewDoc || !previewDoc.head) {
            return;
        }
        if (previewDoc.getElementById('ecc-editor-styles')) {
            return;
        }
        var style = previewDoc.createElement('style');
        style.id = 'ecc-editor-styles';
        // Give child containers a visible minimum height in the editor so the
        // carousel area doesn't collapse to a 0-height grey line.
        style.textContent =
            '.elementor-widget-' + WIDGET_TYPE + ' .ecc-swiper-container {' +
            '  min-height: 120px;' +
            '}' +
            '.elementor-widget-' + WIDGET_TYPE + ' .swiper-slide.ecc-slide {' +
            '  min-height: 120px;' +
            '  outline: 1px dashed #c4cad4;' +
            '}';
        previewDoc.head.appendChild(style);
    }

    function getPreviewDocument() {
        if (elementor.$preview && elementor.$preview[0]) {
            return elementor.$preview[0].contentDocument
                || elementor.$preview[0].contentWindow.document;
        }
        var frame = document.getElementById('elementor-preview-iframe');
        if (frame) {
            return frame.contentDocument || frame.contentWindow.document;
        }
        return null;
    }

    elementor.on('preview:loaded', function () {
        setTimeout(injectEditorStyles, 300);
    });

    elementor.on('preview:afterLoading', function () {
        setTimeout(injectEditorStyles, 300);
    });

})();
