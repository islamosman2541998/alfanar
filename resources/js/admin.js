import * as bootstrap from 'bootstrap';
import { bootNotify } from './shared/notify';
import { confirmAction } from './admin/confirm';

window.bootstrap = bootstrap;
window.confirmAction = confirmAction;

bootNotify();

// Bootstrap modals controlled from Livewire: $this->dispatch('modal', id: 'formModal', action: 'show')
window.addEventListener('modal', ({ detail }) => {
    const el = document.getElementById(detail.id);
    if (!el) return;
    bootstrap.Modal.getOrCreateInstance(el)[detail.action === 'hide' ? 'hide' : 'show']();
});

// Mobile sidebar
document.addEventListener('click', (e) => {
    if (e.target.closest('[data-sidebar-toggle]')) {
        document.body.classList.toggle('sidebar-open');
    } else if (e.target.closest('.sidebar-backdrop')) {
        document.body.classList.remove('sidebar-open');
    }
});
