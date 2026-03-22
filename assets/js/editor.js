(function () {
    'use strict';

    if (typeof elementor === 'undefined') {
        return;
    }

    var WIDGET_TYPE = 'ecc_container_carousel';
    var SLIDES_WIDGET_TYPE = 'ecc_slides_carousel';

    // -------------------------------------------------------------------------
    // Fix ForceMethodImplementation: getEmptyView (Elementor 3.35+)
    // -------------------------------------------------------------------------
    // Elementor 3.35+ added @ForceMethodImplementation to ElementBase.getEmptyView().
    // Third-party widgets without a registered JS element type fall back to the
    // base Widget type (editor.js:30076) which inherits the throwing method from
    // ElementBase (editor.js:30735). We intercept getElementTypeClass() for our
    // widget types and wrap the returned type object so getEmptyView() returns a
    // valid React function component instead of throwing.
    // -------------------------------------------------------------------------
    var ECCEmptyComponent = function () {
        return null;
    };

    function patchGetEmptyView() {
        if (!elementor || !elementor.elementsManager) {
            return false;
        }

        var manager = elementor.elementsManager;

        if (manager.__eccPatched) {
            return true;
        }

        manager.__eccPatched = true;

        var origGetElementTypeClass = manager.getElementTypeClass;

        if (typeof origGetElementTypeClass !== 'function') {
            return false;
        }

        manager.getElementTypeClass = function (type) {
            var orig = origGetElementTypeClass.call(this, type);

            if (orig && (type === WIDGET_TYPE || type === SLIDES_WIDGET_TYPE)) {
                return {
                    getModel: function () { return orig.getModel(); },
                    getView: function () { return orig.getView(); },
                    getType: function () { return orig.getType(); },
                    getEmptyView: function () { return ECCEmptyComponent; }
                };
            }

            return orig;
        };

        return true;
    }

    if (!patchGetEmptyView()) {
        var attempts = 0;
        var iv = setInterval(function () {
            if (patchGetEmptyView() || ++attempts > 500) {
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

    function patchSlideClasses() {
        var previewDoc = getPreviewDocument();
        if (!previewDoc || !previewDoc.body) {
            return;
        }

        previewDoc.querySelectorAll('.ecc-slide-wrapper').forEach(function (wrapper) {
            Array.prototype.forEach.call(wrapper.children, function (child) {
                if (child.nodeType === 1 && !child.classList.contains('swiper-slide')) {
                    child.classList.add('swiper-slide', 'ecc-slide');
                }
            });
        });
    }

    function observeSlideClasses() {
        var previewDoc = getPreviewDocument();
        if (!previewDoc || !previewDoc.body || !window.MutationObserver) {
            return;
        }

        if (previewDoc.getElementById('ecc-slide-observer-active')) {
            return;
        }

        var marker = previewDoc.createElement('meta');
        marker.id = 'ecc-slide-observer-active';
        previewDoc.head.appendChild(marker);

        new MutationObserver(function (mutations) {
            var needsPatch = false;
            for (var i = 0; i < mutations.length; i++) {
                if (mutations[i].addedNodes.length > 0) {
                    needsPatch = true;
                    break;
                }
            }
            if (needsPatch) {
                patchSlideClasses();
            }
        }).observe(previewDoc.body, { childList: true, subtree: true });
    }

    elementor.on('preview:loaded', function () {
        setTimeout(function () {
            injectEditorStyles();
            patchSlideClasses();
            observeSlideClasses();
        }, 300);
    });

    elementor.on('preview:afterLoading', function () {
        setTimeout(function () {
            injectEditorStyles();
            patchSlideClasses();
            observeSlideClasses();
        }, 300);
    });

})();
