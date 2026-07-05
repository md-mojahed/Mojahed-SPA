/**
 * mojahed/spa — SPA JS Core
 * Alpine.js + Axios + Bootstrap SPA system for Laravel
 * Author: Md. Mojahedul Islam
 */

// ─────────────────────────────────────────────────────────────
// SweetAlert Toast Helper
// ─────────────────────────────────────────────────────────────

const SpaToast = (typeof Swal !== 'undefined') ? Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    didOpen: (toast) => {
        toast.addEventListener('mouseenter', Swal.stopTimer);
        toast.addEventListener('mouseleave', Swal.resumeTimer);
    }
}) : null;

function spaToast({ type = 'success', title = '', seconds = 3 } = {}) {
    if (!SpaToast) { console.warn('[spa] SweetAlert2 not loaded.'); return; }
    SpaToast.fire({ icon: type, title: title, timer: seconds * 1000 });
}

// ─────────────────────────────────────────────────────────────
// Script Extraction & Execution
// ─────────────────────────────────────────────────────────────

function spaGetScriptCode(html) {
    return html.match(/<script[^>]*>([\s\S]*?)<\/script>/i)?.[1]?.trim() || '';
}

function spaRunJs(str) {
    try {
        // eslint-disable-next-line no-new-func
        new Function(str)();
    } catch (e) {
        console.warn('[spa] Script execution error:', e);
    }
}

function spaRunScriptCode(html) {
    const code = spaGetScriptCode(html);
    if (code) spaRunJs(code);
}

// ─────────────────────────────────────────────────────────────
// On-Success Handler
// ─────────────────────────────────────────────────────────────

function spaHandleSuccess(onSuccess, responseData) {

    if (onSuccess.toast) {
        let message = onSuccess.toast;
        // If server returned a message, prefer it
        if (responseData && typeof responseData === 'object' && responseData.message) {
            message = responseData.message;
        }
        spaToast({ type: 'success', title: message });
    }

    if (onSuccess.reload) {
        const targetId = onSuccess.reload.replace('#', '');
        window.dispatchEvent(new CustomEvent('spa-reload', { detail: { id: targetId } }));
    }

    if (onSuccess.close) {
        const id = onSuccess.close.replace('#', '');
        const el = document.getElementById(id);
        if (el) {
            // Bootstrap modal
            if (el.classList.contains('modal')) {
                const modal = bootstrap.Modal.getInstance(el);
                if (modal) modal.hide();
            }
            // Bootstrap offcanvas
            if (el.classList.contains('offcanvas')) {
                const oc = bootstrap.Offcanvas.getInstance(el);
                if (oc) oc.hide();
            }
        }
    }

    if (onSuccess.redirect) {
        window.location.href = onSuccess.redirect;
    }

    if (onSuccess.emit) {
        window.dispatchEvent(new CustomEvent(onSuccess.emit, { detail: responseData }));
    }
}

// ─────────────────────────────────────────────────────────────
// Core SPA Action (used by spa-btn and spa-link)
// ─────────────────────────────────────────────────────────────

async function spaAction(options = {}) {
    const {
        url    = '',
        method = 'get',
        target = '',
        modal  = '',
        offcanvas = '',
        params = {},
        data   = {},
        confirm   = {},
        onSuccess = {}
    } = options;

    if (!url) {
        console.warn('[spa] spaAction: no url provided.');
        return;
    }

    // Confirm dialog
    if (confirm.enabled) {
        const result = await Swal.fire({
            title:             confirm.title  || 'Are you sure?',
            text:              confirm.text   || '',
            icon:              confirm.type   || 'warning',
            showCancelButton:  true,
            confirmButtonText: confirm.ok     || 'Yes, proceed!',
            cancelButtonText:  confirm.cancel || 'Cancel',
            confirmButtonColor: '#d33',
            cancelButtonColor:  '#6c757d',
        });

        if (!result.isConfirmed) return;
    }

    // Determine load target
    const loadTarget = target || modal || offcanvas;
    const loadType   = target ? 'target' : (modal ? 'modal' : 'offcanvas');

    // If loading into a container, dispatch load event
    if (loadTarget) {
        window.dispatchEvent(new CustomEvent('spa-load', {
            detail: { id: loadTarget, url, method, params, loadType }
        }));
        return;
    }

    // No container — just fire the request (e.g. delete)
    try {
        const response = await spaRequest(url, method, data, params);
        spaHandleSuccess(onSuccess, response.data);
    } catch (e) {
        const msg = e?.response?.data?.message || 'Something went wrong.';
        spaToast({ type: 'error', title: msg });
    }
}

// ─────────────────────────────────────────────────────────────
// HTTP Request Helper
// ─────────────────────────────────────────────────────────────

async function spaRequest(url, method = 'get', data = {}, params = {}) {
    const config = { params };

    if (['post', 'put', 'patch', 'delete'].includes(method)) {
        return await axios[method](url, data, config);
    }

    return await axios.get(url, config);
}

// ─────────────────────────────────────────────────────────────
// Alpine: spaContainer — used by spa-modal, spa-offcanvas, spa-target
// ─────────────────────────────────────────────────────────────

function spaContainer(id, type) {
    return {
        id:      id,
        type:    type,
        content: '',
        loading: false,
        _url:    '',
        _method: 'get',
        _params: {},

        init() {
            // Listen for reload events targeting this container
            window.addEventListener('spa-reload', (e) => {
                if (e.detail.id === this.id && this._url) {
                    this.spaLoad({ url: this._url, method: this._method, params: this._params });
                }
            });
        },

        async spaLoad({ url, method = 'get', params = {} }) {
            this._url    = url;
            this._method = method;
            this._params = params;
            this.loading = true;
            this.content = '';

            // Open Bootstrap modal/offcanvas if needed
            if (this.type === 'modal') {
                const modal = new bootstrap.Modal(document.getElementById(this.id));
                modal.show();
            }
            if (this.type === 'offcanvas') {
                const oc = new bootstrap.Offcanvas(document.getElementById(this.id));
                oc.show();
            }

            try {
                const response = await spaRequest(url, method, {}, params);
                this.content = response.data;
                spaRunScriptCode(response.data);
            } catch (e) {
                this.content = '<div class="p-3 text-danger"><i class="fas fa-exclamation-circle me-1"></i>Failed to load content.</div>';
                console.error('[spa] Load error:', e);
            } finally {
                this.loading = false;
            }
        },

        spaHandleLoad(e) {
            if (e.detail.id === this.id) {
                this.spaLoad({
                    url:    e.detail.url,
                    method: e.detail.method || 'get',
                    params: e.detail.params || {}
                });
            }
        },

        spaHandleReset(e) {
            if (e.detail.id === this.id) {
                this.content = '';
                this.loading = false;
            }
        }
    };
}

// ─────────────────────────────────────────────────────────────
// Alpine: spaForm — used by spa-form component
// ─────────────────────────────────────────────────────────────

function spaForm(options = {}) {
    const { url, method = 'post', model = 'formData', confirm = {}, onSuccess = {} } = options;

    return {
        submitting: false,
        errors: {},

        async submit() {
            // Confirm if needed
            if (confirm.enabled) {
                const result = await Swal.fire({
                    title:             confirm.title  || 'Are you sure?',
                    text:              confirm.text   || '',
                    icon:              confirm.type   || 'question',
                    showCancelButton:  true,
                    confirmButtonText: confirm.ok     || 'Yes, proceed!',
                    cancelButtonText:  confirm.cancel || 'Cancel',
                });
                if (!result.isConfirmed) return;
            }

            this.submitting = true;
            this.errors     = {};

            // Get form data from the named Alpine model if available
            const formData = this[model] ?? {};

            try {
                const response = await spaRequest(url, method, formData);

                if (response.data?.status === 'error' || response.data?.errors) {
                    this.errors = response.data.errors ?? {};
                    if (response.data?.message) {
                        spaToast({ type: 'error', title: response.data.message });
                    }
                    return;
                }

                spaHandleSuccess(onSuccess, response.data);

            } catch (e) {
                if (e?.response?.status === 422) {
                    this.errors = e.response.data?.errors ?? {};
                } else {
                    const msg = e?.response?.data?.message || 'Something went wrong.';
                    spaToast({ type: 'error', title: msg });
                }
            } finally {
                this.submitting = false;
            }
        }
    };
}

// ─────────────────────────────────────────────────────────────
// Alpine: spa() — base component, replaces x_alpine_init()
// ─────────────────────────────────────────────────────────────

function spa() {
    return (window.SpaData || (() => ({
        url_triggers: [],

        init() {
            spaDefaultUrlTrigger.call(this);
        },

        // Convenience wrappers
        spaToast: (options) => spaToast(options),
        spaAction: (options) => spaAction(options),
    })))();
}

// ─────────────────────────────────────────────────────────────
// URL Parameter Utilities
// ─────────────────────────────────────────────────────────────

function spaGetParam(key, defaultValue = null) {
    const value = new URLSearchParams(window.location.search).get(key);
    return value !== null ? value : defaultValue;
}

function spaSetParam(key, value) {
    const url = new URL(window.location.href);
    url.searchParams.set(key, value);
    window.history.pushState({ path: url.href }, '', url.href);
    spaParamChanged();
}

function spaRemoveParam(key) {
    const url = new URL(window.location.href);
    url.searchParams.delete(key);
    window.history.pushState({ path: url.href }, '', url.href);
    spaParamChanged();
}

function spaGetAllParams() {
    const params = {};
    for (const [key, value] of new URLSearchParams(window.location.search).entries()) {
        params[key] = value;
    }
    return params;
}

function spaParamChanged() {
    const all = document.querySelectorAll('[x-data]');
    let mostNested = null, maxDepth = -1;

    all.forEach(el => {
        let depth = 0, cur = el;
        while (cur.parentElement) { depth++; cur = cur.parentElement; }
        if (depth > maxDepth) { maxDepth = depth; mostNested = el; }
    });

    if (!mostNested) return;

    const alpineData = Alpine.$data(mostNested);
    if (!alpineData || typeof alpineData.paramChanged !== 'function') return;
    alpineData.paramChanged();
}

// ─────────────────────────────────────────────────────────────
// Default URL Trigger (ported from your common.js)
// ─────────────────────────────────────────────────────────────

function spaDefaultUrlTrigger(index = 0) {
    this.url_triggers[index] = () => {
        const delay       = spaGetParam('delay',  0) * 1000;
        const bdelay      = spaGetParam('bdelay', 0) * 1000;
        const adelay      = spaGetParam('adelay', 0) * 1000;
        const before      = spaGetParam('before',  null);
        const beforeEvent = spaGetParam('bevent',  null);
        const after       = spaGetParam('after',   null);
        const afterEvent  = spaGetParam('aevent',  null);
        const call        = spaGetParam('call',    null);
        const callType    = spaGetParam('type',    'alpine');
        const withArgs    = spaGetParam('with',    '').split(',').map(s => s.trim()).filter(Boolean);

        setTimeout(() => {
            if (before && beforeEvent) {
                setTimeout(() => {
                    const el = document.querySelector(before);
                    if (el) el.dispatchEvent(new Event(beforeEvent));
                }, bdelay);
            }

            setTimeout(() => {
                if (call && callType === 'alpine') {
                    const obj = [...document.querySelectorAll('[x-data]')]
                        .map(el => Alpine.$data(el))
                        .find(d => d[call] && typeof d[call] === 'function');
                    if (obj) obj[call](...withArgs);
                    else console.warn('[spa] Alpine method not found:', call);
                } else if (call && typeof window[call] === 'function') {
                    window[call](...withArgs);
                }
            }, delay + 500);

            if (after && afterEvent) {
                setTimeout(() => {
                    const el = document.querySelector(after);
                    if (el) el.dispatchEvent(new Event(afterEvent));
                }, adelay);
            }
        }, 1000);
    };

    this.paramChanged = () => {
        const triggers = [...this.url_triggers].reverse();
        const called = [];
        triggers.forEach(trigger => {
            if (typeof trigger === 'function' && !called.includes(trigger.toString())) {
                trigger();
                called.push(trigger.toString());
            }
        });
    };

    this.paramChanged();
}

// ─────────────────────────────────────────────────────────────
// Global Aliases (backwards compat & convenience)
// ─────────────────────────────────────────────────────────────

window.spaToast         = spaToast;
window.spaAction        = spaAction;
window.spaContainer     = spaContainer;
window.spaForm          = spaForm;
window.spa              = spa;
window.spaGetParam      = spaGetParam;
window.spaSetParam      = spaSetParam;
window.spaRemoveParam   = spaRemoveParam;
window.spaGetAllParams  = spaGetAllParams;
window.spaParamChanged  = spaParamChanged;
window.spaRunScriptCode = spaRunScriptCode;

// Legacy aliases so existing code doesn't break
window.toast            = spaToast;
window.runScriptCode    = spaRunScriptCode;
window.setParam         = spaSetParam;
window.getParam         = spaGetParam;
window.removeParam      = spaRemoveParam;
window.getAllParams      = spaGetAllParams;
window.paramChanged     = spaParamChanged;
