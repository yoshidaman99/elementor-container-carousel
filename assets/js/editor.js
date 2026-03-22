(function () {
    'use strict';

    var ELEMENTOR_EDITOR = typeof elementor !== 'undefined' && elementor;
    var ECC_SELECTOR = '.elementor-widget-ecc_container_carousel';
    var WRAPPER_SELECTOR = '.ecc-swiper-container';
    var SLIDE_SELECTOR = '.swiper-slide.ecc-slide';

    if (!ELEMENTOR_EDITOR) {
        return;
    }

    elementor.on('panel:open', function (panel) {
        addEditorControls();
    });

    elementor.on('preview:loaded', function () {
        setTimeout(addEditorControls, 500);
    });

    elementor.channels.editor.on('change:widget', function (ctrl) {
        if (ctrl && ctrl.model && ctrl.model.attributes && ctrl.model.attributes.widgetType === 'ecc_container_carousel') {
            setTimeout(addEditorControls, 200);
        }
    });

    elementor.on('preview:afterLoading', function () {
        setTimeout(addEditorControls, 500);
    });

    function addEditorControls() {
        var widgets = document.querySelectorAll(ECC_SELECTOR);
        widgets.forEach(function (widgetEl) {
            if (widgetEl.querySelector('.ecc-editor-add-slide')) {
                return;
            }
            var wrapper = widgetEl.querySelector(WRAPPER_SELECTOR);
            if (!wrapper) {
                return;
            }

            addSlideNumberBadges(widgetEl, wrapper);
            addDeleteButtons(widgetEl, wrapper);
            addSlideButton(widgetEl, wrapper);
        });
    }

    function addSlideNumberBadges(widgetEl, wrapper) {
        var existingBadges = widgetEl.querySelectorAll('.ecc-slide-badge');
        existingBadges.forEach(function (badge) {
            badge.remove();
        });

        var slides = wrapper.querySelectorAll(SLIDE_SELECTOR);
        slides.forEach(function (slide, index) {
            var badge = document.createElement('div');
            badge.className = 'ecc-slide-badge';
            badge.textContent = 'Slide ' + (index + 1);
            badge.style.cssText = 'position:absolute;top:8px;left:8px;background:#1e1e1e;color:#fff;font-size:11px;padding:2px 8px;border-radius:4px;z-index:100;pointer-events:none;font-family:system-ui,sans-serif;letter-spacing:0.5px;';
            slide.style.position = 'relative';
            slide.appendChild(badge);
        });
    }

    function addDeleteButtons(widgetEl, wrapper) {
        var existingBtns = widgetEl.querySelectorAll('.ecc-editor-delete-slide');
        existingBtns.forEach(function (btn) {
            btn.remove();
        });

        var slides = wrapper.querySelectorAll(SLIDE_SELECTOR);
        slides.forEach(function (slide) {
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

    function addNewSlide(widgetEl) {
        var viewId = widgetEl.getAttribute('data-model-cid') || widgetEl.closest('[data-model-cid]')?.getAttribute('data-model-cid');

        if (!viewId) {
            var widgetView = getWidgetView(widgetEl);
            if (widgetView) {
                viewId = widgetView.model.get('id');
            }
        }

        if (viewId) {
            elementor.getPreviewView().addChildElement(viewId, {
                elType: 'container',
                isInner: false,
                elements: []
            }, {});
        }

        setTimeout(function () {
            refreshControls(widgetEl);
        }, 600);
    }

    function deleteSlide(slideEl) {
        var modelId = slideEl.getAttribute('data-model-cid');
        if (!modelId) {
            modelId = slideEl.closest('[data-model-cid]')?.getAttribute('data-model-cid');
        }

        if (modelId) {
            if (typeof $e !== 'undefined') {
                $e.run('document/elements/delete', {
                    container: elementor.getPreviewView().children.findByModelCid(modelId)
                });
            }
        }

        var widgetEl = slideEl.closest(ECC_SELECTOR);
        setTimeout(function () {
            refreshControls(widgetEl);
        }, 600);
    }

    function getWidgetView(widgetEl) {
        var cid = widgetEl.getAttribute('data-model-cid');
        if (!cid) {
            return null;
        }
        return elementor.getPreviewView().children.findByModelCid(cid);
    }

    function refreshControls(widgetEl) {
        if (!widgetEl) {
            return;
        }

        var existingAdd = widgetEl.querySelector('.ecc-editor-add-slide');
        if (existingAdd) {
            existingAdd.remove();
        }

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
        style.textContent = ECC_SELECTOR + ' .elementor-empty-view { display: none; }';
        document.head.appendChild(style);
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', injectEditorStyles);
    } else {
        injectEditorStyles();
    }
})();
