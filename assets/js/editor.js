(function () {
    'use strict';

    if (typeof elementor === 'undefined') {
        return;
    }

    var WIDGET_TYPE = 'ecc_container_carousel';
    var SLIDES_WIDGET_TYPE = 'ecc_slides_carousel';

    // -------------------------------------------------------------------------
    // Fix ForceMethodImplementation for getEmptyView in Elementor 3.35+.
    //
    // Elementor calls getEmptyView() on the *element type class* returned by
    // elementor.elementsManager.getElementTypeClass(widgetType) — NOT on the
    // Widget view prototype.  Custom widgets have no registered element type
    // so they fall back to the generic "widget" base whose getEmptyView()
    // throws ForceMethodImplementation.
    //
    // We monkey-patch getElementTypeClass so it returns a patched object
    // (that delegates to the original but overrides getEmptyView) for our
    // widget types.  This must run synchronously before any preview rendering.
    // -------------------------------------------------------------------------
    function patchElementsManager() {
        if (!elementor.elementsManager || !elementor.elementsManager.getElementTypeClass) {
            return false;
        }

        var origGetElementTypeClass = elementor.elementsManager.getElementTypeClass;
        if (origGetElementTypeClass.__eccPatched) {
            return true;
        }
        origGetElementTypeClass.__eccPatched = true;

        elementor.elementsManager.getElementTypeClass = function (type) {
            var typeClass = origGetElementTypeClass.call(this, type);

            if (type === WIDGET_TYPE || type === SLIDES_WIDGET_TYPE) {
                if (typeClass && typeof typeClass.getEmptyView === 'function') {
                    try {
                        var test = typeClass.getEmptyView();
                        if (test) {
                            return typeClass;
                        }
                    } catch (e) {
                        // getEmptyView throws — wrap it
                    }
                }

                // Return a proxy that overrides getEmptyView to return null
                // (null = no empty view React component = no error)
                var ProxyType = function () {};
                ProxyType.prototype = typeClass || {};
                ProxyType.prototype.getEmptyView = function () {
                    return null;
                };
                ProxyType.prototype.getType = function () {
                    return type;
                };
                return new ProxyType();
            }

            return typeClass;
        };

        return true;
    }

    // Try immediately, then poll until Elementor modules are available
    if (patchElementsManager()) {
        // already patched
    } else {
        var attempts = 0;
        var iv = setInterval(function () {
            if (patchElementsManager() || ++attempts > 500) {
                clearInterval(iv);
            }
        }, 10);
    }

    // -------------------------------------------------------------------------
    // Add a new container slide to the widget.
    // -------------------------------------------------------------------------
    elementor.channels.editor.on('ecc:addSlide', function (view) {
        var container = view && view.options && view.options.container;

        if (!container) {
            try {
                container = elementor.getPanelView().currentPageView.options.container;
            } catch (e) { return; }
        }

        if (!container || !container.view || !container.model) {
            return;
        }

        var origIsValidChild = container.model.isValidChild;
        var origGetChildType = container.view.getChildType;

        container.model.isValidChild = function (childModel) {
            var elType = childModel && (
                typeof childModel.get === 'function'
                    ? childModel.get('elType')
                    : childModel.elType
            );
            return elType === 'container';
        };

        container.view.getChildType = function () {
            return ['container'];
        };

        try {
            $e.run('document/elements/create', {
                container: container,
                model: {
                    elType: 'container',
                    settings: {},
                },
                options: {
                    edit: false,
                },
            });
        } catch (e) {
            // Swallow – already logged by Elementor's command system.
        } finally {
            container.model.isValidChild = origIsValidChild;
            container.view.getChildType = origGetChildType;
        }
    });

    // -------------------------------------------------------------------------
    // Inject editor-only styles into the preview iframe.
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
