(function () {
    'use strict';

    if (typeof elementor === 'undefined') {
        return;
    }

    var WIDGET_TYPE = 'ecc_container_carousel';

    /**
     * Add a new container slide to the widget.
     *
     * Two guards in Elementor's command pipeline must be bypassed for a
     * Widget_Base element to accept children:
     *
     *   1. document/elements/create → validate()
     *      Calls container.model.isValidChild(childModel).
     *      Widget model always returns false → command aborted.
     *
     *   2. document/elements/create → apply() → view.addElement()
     *      Calls this.getChildType() on the view.
     *      Widget view returns [] → element silently redirected into
     *      the last existing child instead of added as a sibling.
     *
     * We temporarily replace both methods on this specific widget instance
     * (not on the prototype), run the command, then restore them.
     * $e.run is synchronous so the finally block runs after the element
     * has already been created.
     */
    elementor.channels.editor.on('ecc:addSlide', function (view) {
        // view.options.container is a lazy getter that resolves to the
        // Container object of the element currently open in the panel.
        var container = view && view.options && view.options.container;

        if (!container) {
            try {
                container = elementor.getPanelView().currentPageView.options.container;
            } catch (e) { return; }
        }

        if (!container || !container.view || !container.model) {
            return;
        }

        // Stash originals so we can restore them after the command.
        var origIsValidChild  = container.model.isValidChild;
        var origGetChildType  = container.view.getChildType;

        // Patch 1 – bypass validate().
        container.model.isValidChild = function (childModel) {
            var elType = childModel && (
                typeof childModel.get === 'function'
                    ? childModel.get('elType')
                    : childModel.elType
            );
            return elType === 'container';
        };

        // Patch 2 – bypass the childTypes check inside view.addElement().
        container.view.getChildType = function () {
            return ['container'];
        };

        try {
            $e.run('document/elements/create', {
                container: container,
                model: {
                    elType:    'container',
                    settings:  {},
                },
                options: {
                    edit: false,   // Don't auto-open the new slide's panel.
                },
            });
        } catch (e) {
            // Swallow – already logged by Elementor's command system.
        } finally {
            // Restore both methods immediately after the synchronous command.
            container.model.isValidChild = origIsValidChild;
            container.view.getChildType  = origGetChildType;
        }
    });

    // -------------------------------------------------------------------------
    // Patch the JS Widget view for our widget type so Elementor 3.35+ doesn't
    // throw ForceMethodImplementation when the widget has no children.
    //
    // The rendering chain (onPreviewLoaded → Promise.then → renderPreview)
    // runs as a microtask that completes BEFORE any setTimeout macrotask fires.
    // We must patch the prototype synchronously during elementor.init (well
    // before preview:loaded) using an aggressive polling loop.
    // -------------------------------------------------------------------------
    (function patchGetEmptyView() {
        var patched = false;

        function doPatch() {
            try {
                var views = elementor.modules && elementor.modules.elements
                    && elementor.modules.elements.views;
                if (!views || !views.Widget || views.Widget.prototype.__eccPatched) {
                    return !!views && !!views.Widget;
                }
                views.Widget.prototype.__eccPatched = true;

                var origGetEmptyView = views.Widget.prototype.getEmptyView;
                views.Widget.prototype.getEmptyView = function () {
                    var widgetType = this.model && this.model.get
                        && this.model.get('widgetType');
                    if (widgetType === WIDGET_TYPE) {
                        return {
                            title: 'Container Carousel',
                            description: 'Drag containers here to create carousel slides.',
                            icon: 'eicon-slider-push',
                        };
                    }
                    if (typeof origGetEmptyView === 'function') {
                        try { return origGetEmptyView.call(this); }
                        catch (e) { return {}; }
                    }
                    return {};
                };
                return true;
            } catch (e) { return false; }
        }

        if (doPatch()) { return; }

        var attempts = 0;
        var iv = setInterval(function () {
            if (doPatch() || ++attempts > 500) {
                clearInterval(iv);
            }
        }, 10);
    })();

    // -------------------------------------------------------------------------
    // Inject editor-only styles into the preview iframe so child containers
    // don't collapse to a zero-height bar in the canvas.
    // -------------------------------------------------------------------------
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
        // In the editor carousel.min.js never runs, so children don't get
        // swiper-slide/ecc-slide classes. Target the raw Elementor elements.
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
