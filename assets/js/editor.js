(function () {
    'use strict';

    if (typeof elementor === 'undefined') {
        return;
    }

    var WIDGET_TYPE = 'ecc_container_carousel';
    var WIDGET_SELECTOR = '.elementor-widget-' + WIDGET_TYPE;
    var WRAPPER_SELECTOR = '.ecc-swiper-container';
    var SLIDE_SELECTOR = '.swiper-slide.ecc-slide';

    // editor.js runs in the editor admin window; widgets live inside the preview iframe
    function getPreviewDocument() {
        if (elementor.$preview && elementor.$preview[0]) {
            return elementor.$preview[0].contentDocument || elementor.$preview[0].contentWindow.document;
        }
        var frame = document.getElementById('elementor-preview-iframe');
        if (frame) {
            return frame.contentDocument || frame.contentWindow.document;
        }
        return document;
    }

    function getWidgetContainer(widgetEl) {
        var elementId = widgetEl.getAttribute('data-id');
        if (elementId && typeof elementor.getContainer === 'function') {
            return elementor.getContainer(elementId);
        }
        // Fallback for older Elementor without getContainer
        var cid = widgetEl.getAttribute('data-model-cid');
        if (!cid) {
            return null;
        }
        var previewView = elementor.getPreviewView();
        if (!previewView) {
            return null;
        }
        return previewView.children.findByModelCid(cid);
    }

    function getWidgetModelCid(widgetEl) {
        var cid = widgetEl.getAttribute('data-model-cid');
        if (cid) {
            return cid;
        }
        var container = getWidgetContainer(widgetEl);
        if (container && container.model) {
            return container.model.cid;
        }
        return null;
    }

    function addNewSlide(widgetEl) {
        var container = getWidgetContainer(widgetEl);
        if (!container) {
            return;
        }

        if (typeof $e !== 'undefined') {
            $e.run('document/elements/create', {
                container: container,
                model: {
                    elType: 'container',
                    settings: {},
                }
            });
        } else {
            var cid = getWidgetModelCid(widgetEl);
            if (!cid) {
                return;
            }
            elementor.getPreviewView().addChildElement(cid, {
                elType: 'container',
                isInner: false,
                elements: []
            }, {});
        }

        setTimeout(function () {
            refreshEditorControls();
        }, 600);
    }

    function deleteSlide(slideEl) {
        var elementId = slideEl.getAttribute('data-id');

        if (typeof $e !== 'undefined' && elementId && typeof elementor.getContainer === 'function') {
            var slideContainer = elementor.getContainer(elementId);
            if (!slideContainer) {
                return;
            }
            $e.run('document/elements/delete', {
                container: slideContainer
            });
        } else {
            var modelId = slideEl.getAttribute('data-model-cid');
            if (!modelId) {
                return;
            }
            var previewView = elementor.getPreviewView();
            if (!previewView) {
                return;
            }
            var childView = previewView.children.findByModelCid(modelId);
            if (!childView) {
                return;
            }
            childView.remove();
        }

        setTimeout(function () {
            refreshEditorControls();
        }, 600);
    }

    function addSlideNumberBadges(wrapper) {
        var previewDoc = getPreviewDocument();
        var slides = wrapper.querySelectorAll(SLIDE_SELECTOR);
        slides.forEach(function (slide, index) {
            if (slide.querySelector('.ecc-slide-badge')) {
                return;
            }
            var badge = previewDoc.createElement('div');
            badge.className = 'ecc-slide-badge';
            badge.textContent = 'Slide ' + (index + 1);
            badge.style.cssText = 'position:absolute;top:8px;left:8px;background:#1e1e1e;color:#fff;font-size:11px;padding:2px 8px;border-radius:4px;z-index:100;pointer-events:none;font-family:system-ui,sans-serif;letter-spacing:0.5px;';
            slide.style.position = 'relative';
            slide.appendChild(badge);
        });
    }

    function addDeleteButtons(wrapper) {
        var previewDoc = getPreviewDocument();
        var slides = wrapper.querySelectorAll(SLIDE_SELECTOR);
        slides.forEach(function (slide) {
            if (slide.querySelector('.ecc-editor-delete-slide')) {
                return;
            }
            var btn = previewDoc.createElement('button');
            btn.className = 'ecc-editor-delete-slide';
            btn.innerHTML = '&times;';
            btn.title = 'Delete Slide';
            btn.style.cssText = 'position:absolute;top:6px;right:6px;background:#e04040;color:#fff;border:none;border-radius:50%;width:26px;height:26px;font-size:18px;line-height:1;cursor:pointer;z-index:100;display:flex;align-items:center;justify-content:center;font-family:system-ui,sans-serif;box-shadow:0 2px 6px rgba(0,0,0,0.25);transition:background 0.2s,transform 0.15s;';

            btn.addEventListener('mouseenter', function () {
                btn.style.background = '#c03030';
                btn.style.transform = 'scale(1.1)';
            });
            btn.addEventListener('mouseleave', function () {
                btn.style.background = '#e04040';
                btn.style.transform = 'scale(1)';
            });
            btn.addEventListener('click', function (e) {
                e.preventDefault();
                e.stopPropagation();
                deleteSlide(slide);
            });

            slide.style.position = 'relative';
            slide.appendChild(btn);
        });
    }

    function addSlideButton(widgetEl, wrapper) {
        var previewDoc = getPreviewDocument();
        if (widgetEl.querySelector('.ecc-editor-add-slide')) {
            return;
        }

        var addBtn = previewDoc.createElement('button');
        addBtn.className = 'ecc-editor-add-slide';
        addBtn.innerHTML = '+ Add Slide';
        addBtn.style.cssText = 'display:flex;align-items:center;justify-content:center;width:100%;min-height:60px;border:2px dashed #a0adc8;border-radius:8px;background:rgba(255,255,255,0.7);color:#a0adc8;font-size:14px;font-weight:600;cursor:pointer;margin-top:12px;transition:all 0.2s;font-family:system-ui,sans-serif;letter-spacing:0.3px;';

        addBtn.addEventListener('mouseenter', function () {
            addBtn.style.borderColor = '#54595f';
            addBtn.style.color = '#54595f';
            addBtn.style.background = 'rgba(255,255,255,0.95)';
        });
        addBtn.addEventListener('mouseleave', function () {
            addBtn.style.borderColor = '#a0adc8';
            addBtn.style.color = '#a0adc8';
            addBtn.style.background = 'rgba(255,255,255,0.7)';
        });
        addBtn.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            addNewSlide(widgetEl);
        });

        wrapper.parentNode.appendChild(addBtn);
    }

    function addEditorControls() {
        var previewDoc = getPreviewDocument();
        if (!previewDoc) {
            return;
        }
        var widgets = previewDoc.querySelectorAll(WIDGET_SELECTOR);
        widgets.forEach(function (widgetEl) {
            var wrapper = widgetEl.querySelector(WRAPPER_SELECTOR);
            if (!wrapper) {
                return;
            }

            addSlideNumberBadges(wrapper);
            addDeleteButtons(wrapper);
            addSlideButton(widgetEl, wrapper);
        });
    }

    function refreshEditorControls() {
        var previewDoc = getPreviewDocument();
        if (!previewDoc) {
            return;
        }

        previewDoc.querySelectorAll('.ecc-editor-add-slide').forEach(function (btn) {
            btn.remove();
        });
        previewDoc.querySelectorAll('.ecc-slide-badge').forEach(function (badge) {
            badge.remove();
        });
        previewDoc.querySelectorAll('.ecc-editor-delete-slide').forEach(function (btn) {
            btn.remove();
        });

        setTimeout(function () {
            addEditorControls();
        }, 100);
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
            WIDGET_SELECTOR + ' .elementor-empty-view {' +
            '  display: flex !important;' +
            '  min-height: 80px;' +
            '  align-items: center;' +
            '  justify-content: center;' +
            '  border: 2px dashed #c4cad4;' +
            '  border-radius: 4px;' +
            '  margin: 8px;' +
            '  color: #a0aab5;' +
            '  font-size: 13px;' +
            '  cursor: pointer;' +
            '}' +
            WIDGET_SELECTOR + ' .elementor-empty-view:hover {' +
            '  border-color: #9da5ae;' +
            '  color: #6d7882;' +
            '}' +
            WIDGET_SELECTOR + ' .elementor-empty-view .elementor-empty-view-icon {' +
            '  display: block !important;' +
            '}' +
            WIDGET_SELECTOR + ' .elementor-empty-view .elementor-first-add {' +
            '  display: block !important;' +
            '  text-align: center;' +
            '  width: 100%;' +
            '}' +
            WIDGET_SELECTOR + ' .elementor-sortable-placeholder {' +
            '  background: rgba(95, 122, 255, 0.08) !important;' +
            '  border: 2px dashed #5f7aff !important;' +
            '  border-radius: 4px;' +
            '  min-height: 60px;' +
            '  transition: all 0.2s;' +
            '}' +
            WIDGET_SELECTOR + ' .elementor-widget-content {' +
            '  min-height: 1px;' +
            '}' +
            WIDGET_SELECTOR + ' .elementor-element > .elementor-widget-content {' +
            '  position: relative;' +
            '}';
        previewDoc.head.appendChild(style);
    }

    elementor.on('panel:open', function () {
        addEditorControls();
    });

    elementor.on('preview:loaded', function () {
        setTimeout(function () {
            injectEditorStyles();
            addEditorControls();
        }, 500);
    });

    elementor.channels.editor.on('change:widget', function (ctrl) {
        if (ctrl && ctrl.model && ctrl.model.attributes && ctrl.model.attributes.widgetType === WIDGET_TYPE) {
            setTimeout(addEditorControls, 200);
        }
    });

    elementor.on('preview:afterLoading', function () {
        setTimeout(function () {
            injectEditorStyles();
            addEditorControls();
        }, 500);
    });
})();
