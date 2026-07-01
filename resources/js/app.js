
import Alpine from 'alpinejs';

Alpine.data('adminShell', () => ({
    sidebarOpen: false,
    confirm: {
        open: false,
        title: 'Konfirmasi',
        message: '',
        confirmText: 'Ya, lanjutkan',
        cancelText: 'Batal',
        variant: 'primary',
        form: null,
    },
    askConfirm(message, form, options = {}) {
        this.confirm.message = message;
        this.confirm.form = form;
        this.confirm.title = options.title ?? 'Konfirmasi';
        this.confirm.confirmText = options.confirmText ?? 'Ya, lanjutkan';
        this.confirm.cancelText = options.cancelText ?? 'Batal';
        this.confirm.variant = options.variant ?? 'primary';
        this.confirm.open = true;
    },
    submitConfirm() {
        if (this.confirm.form) {
            this.confirm.form.submit();
        }
        this.cancelConfirm();
    },
    cancelConfirm() {
        this.confirm.open = false;
        this.confirm.form = null;
    },
}));

window.Alpine = Alpine;

Alpine.start();
