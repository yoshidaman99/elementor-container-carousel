(function () {
    'use strict';

    if (typeof elementor === 'undefined') {
        return;
    }

    var WIDGET_TYPE = 'ecc_container_carousel';

    /**
     * WHY we don't use $e.run('document/elements/create') here:
     *
     * Elementor's Widget.prototype.isValidChild always returns false.
     * When the command runs on a widget container it silently redirects the
     * new element to become a child of the *last slide* instead of a new
     * sibling slide.  Nothing visible happens in the canvas.
     *
     * Instead we add directly to the Backbone elements collection (which the
     * navigator and the save-to-DB path both use), then trigger a settings
     * change so Elementor re-runs content_template() and refreshes the canvas.
     */
    elementor.channels.editor.on('ecc:addSlide', function (view) {
        // view.options.container is set via a getter in Elementor's control view
        var container = view && view.options && view.options.container;

        // Fallback: read from the panel page view (works in all 3.x versions)
        if (!container) {
            try {
                container = elementor.getPanelView().currentPageView.options.container;
            } catch (e) { /* ignore */ }
        }

        if (!container || !container.model) {
            return;
        }

        var elements = container.model.get('elements');
        if (!elements) {
            return;
        }

        // Add a new container slide directly to the Backbone collection.
        // This bypasses isValidChild and goes straight to the data layer.
        var newId = elementor.helpers.getUniqueID();
        elements.add({
            id: newId,
            elType: 'container',
            settings: {},
            elements: [],
            isInner: false,
        });

        // Flag the document as having unsaved changes.
        if (elementor.saver) {
            elementor.saver.setFlagEditorChange();
        }

        // Trigger a settings change so Elementor re-runs content_template()
        // and the canvas reflects the new slide.
        if (container.settings) {
            container.settings.trigger('change');
        } else {
            container.model.trigger('change');
        }
    });

    // ---------------------------------------------------------------------------
    // Inject editor-only styles into the preview iframe so slides don't collapse
    // ---------------------------------------------------------------------------
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
        // In the editor, carousel.min.js never runs so children never get
        // the swiper-slide / ecc-slide classes.  Target the raw Elementor
        // container elements that are direct children of the slides wrapper.
        style.textContent =
            '.elementor-widget-' + WIDGET_TYPE + ' .ecc-swiper-container {' +
            '  min-height: 120px;' +
            '}' +
            '.elementor-widget-' + WIDGET_TYPE + ' .ecc-slides-wrapper > .elementor-element {' +
            '  min-height: 120px;' +
            '  outline: 1px dashed #c4cad4;' +
            '}';
        previewDoc.head.appendChild(style);
    }

    elementor.on('preview:loaded', function () {
        setTimeout(injectEditorStyles, 300);
    });

    elementor.on('preview:afterLoading', function () {
        setTimeout(injectEditorStyles, 300);
    });

})();
