import $ from 'jquery';
import toastr from 'toastr';

window.$ = window.jQuery = $;

const isRtl = document.documentElement.dir === 'rtl';

toastr.options = {
    closeButton: true,
    progressBar: true,
    newestOnTop: true,
    preventDuplicates: true,
    escapeHtml: true,
    rtl: isRtl,
    positionClass: isRtl ? 'toast-top-left' : 'toast-top-right',
    timeOut: 3500,
    extendedTimeOut: 1500,
    showDuration: 200,
    hideDuration: 200,
};

const TYPES = ['success', 'error', 'warning', 'info'];

/** Single entry point for toasts: notify({ type, message }) */
export function notify({ type = 'success', message = '' } = {}) {
    if (!message) return;
    // A success replaces earlier warnings about the same action
    if (type === 'success') toastr.clear();
    toastr[TYPES.includes(type) ? type : 'info'](message);
}

export function bootNotify() {
    window.notify = notify;

    // Browser events dispatched from Livewire: $this->dispatch('toast', ...)
    window.addEventListener('toast', (e) => notify(e.detail));

    // Flashed before a redirect (rendered once by the layout)
    if (window.__toast) {
        notify(window.__toast);
        window.__toast = null;
    }
}
