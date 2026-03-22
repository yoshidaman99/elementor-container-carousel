(function () {
    'use strict';

    if (typeof elementor === 'undefined') {
        return;
    }

    var WIDGET_TYPE = 'ecc_container_carousel';
    var WIDGET_SELECTOR = '.elementor-widget-' + WIDGET_TYPE;
    var WRAPPER_SELECTOR = '.ecc-swiper-container';
    var SLIDE_SELECTOR = '.swiper-slide.ecc-slide';

    function getWidgetContainer(widgetEl) {
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
        var cid = getWidgetModelCid(widgetEl);
        if (!cid) {
            return;
        }

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
            var allChildren = previewView.children._views;
            for (var key in allChildren) {
                if (allChildren.hasOwnProperty(key)) {
                    var view = allChildren[key];
                    if (view && view.$el && view.$el.is(slideEl)) {
                        childView = view;
                        break;
                    }
                    if (view && view.$el && view.$el.find(slideEl).length) {
                        childView = view;
                        break;
                    }
                }
            }
        }

        if (!childView) {
            return;
        }

        if (typeof $e !== 'undefined') {
            $e.run('document/elements/delete', {
                container: childView
            });
        } else {
            childView.remove();
        }

        var widgetEl = slideEl.closest(WIDGET_SELECTOR);
        setTimeout(function () {
            refreshEditorControls();
        }, 600);
    }

    function addSlideNumberBadges(wrapper) {
        var slides = wrapper.querySelectorAll(SLIDE_SELECTOR);
        slides.forEach(function (slide, index) {
            if (slide.querySelector('.ecc-slide-badge')) {
                return;
            }
            var badge = document.createElement('div');
            badge.className = 'ecc-slide-badge';
            badge.textContent = 'Slide ' + (index + 1);
            badge.style.cssText = 'position:absolute;top:8px;left:8px;background:#1e1e1e;color:#fff;font-size:11px;padding:2px 8px;border-radius:4px;z-index:100;pointer-events:none;font-family:system-ui,sans-serif;letter-spacing:0.5px;';
            slide.style.position = 'relative';
            slide.appendChild(badge);
        });
    }

    function addDeleteButtons(wrapper) {
        var slides = wrapper.querySelectorAll(SLIDE_SELECTOR);
        slides.forEach(function (slide) {
            if (slide.querySelector('.ecc-editor-delete-slide')) {
                return;
            }
            var btn = document.createElement('button');
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
        if (widgetEl.querySelector('.ecc-editor-add-slide')) {
            return;
        }

        var addBtn = document.createElement('button');
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
        var widgets = document.querySelectorAll(WIDGET_SELECTOR);
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
        var existingAddBtns = document.querySelectorAll('.ecc-editor-add-slide');
        existingAddBtns.forEach(function (btn) {
            btn.remove();
        });

        var existingBadges = document.querySelectorAll('.ecc-slide-badge');
        existingBadges.forEach(function (badge) {
            badge.remove();
        });

        var existingDeleteBtns = document.querySelectorAll('.ecc-editor-delete-slide');
        existingDeleteBtns.forEach(function (btn) {
            btn.remove();
        });

        setTimeout(function () {
            addEditorControls();
        }, 100);
    }

    function injectEditorStyles() {
        if (document.getElementById('ecc-editor-styles')) {
            return;
        }
        var style = document.createElement('style');
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
        document.head.appendChild(style);
    }

    elementor.on('panel:open', function () {
        addEditorControls();
    });

    elementor.on('preview:loaded', function () {
        setTimeout(addEditorControls, 500);
    });

    elementor.channels.editor.on('change:widget', function (ctrl) {
        if (ctrl && ctrl.model && ctrl.model.attributes && ctrl.model.attributes.widgetType === WIDGET_TYPE) {
            setTimeout(addEditorControls, 200);
        }
    });

    elementor.on('preview:afterLoading', function () {
        setTimeout(addEditorControls, 500);
    });

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', injectEditorStyles);
    } else {
        injectEditorStyles();
    }
})();
