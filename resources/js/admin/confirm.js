import Swal from 'sweetalert2';

const isRtl = document.documentElement.dir === 'rtl';

const swal = Swal.mixin({
    buttonsStyling: false,
    reverseButtons: isRtl,
    customClass: {
        popup: 'swal-brand',
        confirmButton: 'btn btn-brand',
        cancelButton: 'btn btn-outline-secondary',
        actions: 'gap-2',
    },
});

/**
 * Confirmation dialog. Usage in Blade:
 * x-on:click="confirmAction(() => $wire.delete(5))"
 */
export function confirmAction(callback, options = {}) {
    const t = window.i18n || {};

    swal.fire({
        icon: options.icon || 'warning',
        title: options.title || t.confirmTitle || 'Are you sure?',
        text: options.text || t.confirmText || '',
        showCancelButton: true,
        confirmButtonText: options.confirm || t.confirmButton || 'Yes',
        cancelButtonText: options.cancel || t.cancelButton || 'Cancel',
        focusCancel: true,
    }).then((result) => {
        if (result.isConfirmed) callback();
    });
}
