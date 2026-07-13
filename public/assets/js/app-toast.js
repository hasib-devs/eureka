/*
 * app-toast — a tiny, dependency-free client-side toast that visually matches the
 * mckenziearts/laravel-notify "toast" preset (white card, coloured left border, heroicon,
 * title + message, top-right, auto-dismiss). Used so JS/AJAX notifications look identical to
 * the server-flashed notify() toasts. Also shims jQuery-Toast ($.toast) so existing call sites
 * keep working unchanged.
 */
(function () {
    if (window.appToast) return; // load once

    var CSS =
        '.app-toast-wrap{position:fixed;top:0;right:0;padding:1.5rem;z-index:99999;display:flex;flex-direction:column;align-items:flex-end;gap:.75rem;pointer-events:none;max-width:100%;}' +
        '.app-toast{pointer-events:auto;width:24rem;max-width:calc(100vw - 3rem);background:#fff;border-radius:.5rem;box-shadow:0 10px 15px -3px rgba(0,0,0,.1),0 4px 6px -4px rgba(0,0,0,.1);border-left:4px solid #9ca3af;overflow:hidden;transform:translateX(1rem);opacity:0;transition:transform .3s cubic-bezier(.22,.61,.36,1),opacity .3s ease;font-family:-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,Helvetica,Arial,sans-serif;}' +
        '.app-toast.is-show{transform:translateX(0);opacity:1;}' +
        '.app-toast--success{border-left-color:#22c55e;}.app-toast--warning{border-left-color:#eab308;}.app-toast--info{border-left-color:#3b82f6;}.app-toast--error{border-left-color:#ef4444;}' +
        '.app-toast__inner{padding:1rem;display:flex;align-items:flex-start;}' +
        '.app-toast__icon{width:1.5rem;height:1.5rem;flex-shrink:0;}' +
        '.app-toast--success .app-toast__icon{color:#4ade80;}.app-toast--warning .app-toast__icon{color:#eab308;}.app-toast--info .app-toast__icon{color:#3b82f6;}.app-toast--error .app-toast__icon{color:#ef4444;}' +
        '.app-toast__body{margin-left:1rem;flex:1 1 0%;min-width:0;}' +
        '.app-toast__title{font-size:.875rem;line-height:1.25rem;font-weight:600;color:#111827;margin:0;}' +
        '.app-toast__msg{font-size:.875rem;line-height:1.25rem;color:#6b7280;margin:.25rem 0 0;word-wrap:break-word;}' +
        '.app-toast__close{margin-left:1rem;flex-shrink:0;background:none;border:none;padding:0;cursor:pointer;color:#9ca3af;display:inline-flex;}' +
        '.app-toast__close:hover{color:#6b7280;}';

    var ICONS = {
        success: '<path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>',
        warning: '<path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/>',
        info: '<path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z"/>',
        error: '<path stroke-linecap="round" stroke-linejoin="round" d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>',
    };
    var TITLES = { success: 'Success', warning: 'Warning', info: 'Info', error: 'Error' };

    function injectCss() {
        if (document.getElementById('app-toast-css')) return;
        var s = document.createElement('style');
        s.id = 'app-toast-css';
        s.textContent = CSS;
        document.head.appendChild(s);
    }

    var wrap = null;
    function ensureWrap() {
        if (!wrap || !document.body.contains(wrap)) {
            wrap = document.createElement('div');
            wrap.className = 'app-toast-wrap';
            document.body.appendChild(wrap);
        }
        return wrap;
    }

    function normalizeType(t) {
        t = String(t || 'success').toLowerCase();
        if (t === 'danger') t = 'error';
        if (!ICONS[t]) t = 'info';
        return t;
    }

    function escapeHtml(s) {
        return String(s == null ? '' : s).replace(/[&<>"']/g, function (c) {
            return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c];
        });
    }

    window.appToast = function (type, message, title) {
        injectCss();
        type = normalizeType(type);
        var w = ensureWrap();

        var el = document.createElement('div');
        el.className = 'app-toast app-toast--' + type;
        el.setAttribute('role', 'alert');
        el.innerHTML =
            '<div class="app-toast__inner">' +
            '<svg class="app-toast__icon" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">' + ICONS[type] + '</svg>' +
            '<div class="app-toast__body">' +
            '<p class="app-toast__title">' + escapeHtml(title || TITLES[type]) + '</p>' +
            (message ? '<p class="app-toast__msg">' + escapeHtml(message) + '</p>' : '') +
            '</div>' +
            '<button type="button" class="app-toast__close" aria-label="Close">' +
            '<svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M6 6l12 12M18 6L6 18"/></svg>' +
            '</button>' +
            '</div>';

        w.appendChild(el);
        requestAnimationFrame(function () { el.classList.add('is-show'); });

        var removed = false;
        function remove() {
            if (removed) return;
            removed = true;
            el.classList.remove('is-show');
            setTimeout(function () { if (el.parentNode) el.parentNode.removeChild(el); }, 320);
        }
        el.querySelector('.app-toast__close').addEventListener('click', remove);
        setTimeout(remove, 5000);
        return el;
    };

    // Shim jQuery-Toast so existing $.toast({heading, text, icon}) call sites render the same toast.
    if (window.jQuery) {
        window.jQuery.toast = function (opts) {
            opts = opts || {};
            window.appToast(opts.icon, opts.text, opts.heading);
        };
    }
})();
