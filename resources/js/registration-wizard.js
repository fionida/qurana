export function syncPaymentVisibility() {
    const isTransfer = document.querySelector('input[name="metode_pembayaran"][value="transfer"]')?.checked;
    document.getElementById('bukti-transfer-field')?.classList.toggle('hidden', !isTransfer);
}

function fieldValue(name) {
    const form = document.getElementById('registration-form');
    if (!form) {
        return '';
    }

    const el = form.elements.namedItem(name);
    if (!el) {
        return '';
    }

    if (el instanceof RadioNodeList) {
        return el.value ?? '';
    }

    if (el instanceof HTMLSelectElement) {
        return el.options[el.selectedIndex]?.text?.trim() ?? el.value;
    }

    return el.value?.trim() ?? '';
}

function validatePanel(step) {
    const panel = document.getElementById(`reg-step-${step}`);
    if (!panel) {
        return true;
    }

    const fields = panel.querySelectorAll('input, select, textarea');
    for (const el of fields) {
        if (el.type === 'hidden') {
            continue;
        }

        if (el.disabled) {
            continue;
        }

        if (!el.checkValidity()) {
            el.reportValidity();
            return false;
        }
    }

    if (step === 5) {
        syncPaymentVisibility();
        const isTransfer = document.querySelector('input[name="metode_pembayaran"][value="transfer"]')?.checked;
        const bukti = document.querySelector('input[name="bukti_transfer"]');
        if (isTransfer && bukti && bukti.files.length === 0) {
            bukti.setCustomValidity('Bukti transfer wajib diunggah untuk metode transfer.');
            bukti.reportValidity();
            bukti.setCustomValidity('');
            return false;
        }
    }

    return true;
}

document.addEventListener('alpine:init', () => {
    window.Alpine.data('registrationWizard', (config) => ({
        step: config.initialStep ?? 1,
        maxStep: 6,
        declaration: false,
        stepError: '',
        files: {
            pas_foto: config.files?.pas_foto ?? '',
            dokumen_ktp: config.files?.dokumen_ktp ?? '',
            dokumen_surat_rekomendasi: config.files?.dokumen_surat_rekomendasi ?? '',
            bukti_transfer: config.files?.bukti_transfer ?? '',
        },
        steps: [
            { num: 1, label: 'Data Diri' },
            { num: 2, label: 'Alamat' },
            { num: 3, label: 'Lembaga' },
            { num: 4, label: 'Dokumen' },
            { num: 5, label: 'Pembayaran' },
            { num: 6, label: 'Review' },
        ],

        isActive(num) {
            return this.step === num;
        },

        isDone(num) {
            return this.step > num;
        },

        goNext() {
            this.stepError = '';
            if (!validatePanel(this.step)) {
                return;
            }
            if (this.step < this.maxStep) {
                this.step += 1;
            }
            if (this.step === 5) {
                syncPaymentVisibility();
            }
        },

        goPrev() {
            this.stepError = '';
            if (this.step > 1) {
                this.step -= 1;
            }
        },

        goToStep(num) {
            if (num < this.step) {
                this.step = num;
            }
        },

        onFileChange(event, key) {
            const file = event.target.files?.[0];
            this.files[key] = file ? file.name : '';
        },

        triggerFile(refName) {
            this.$refs[refName]?.click();
        },

        reviewRows() {
            const jk = fieldValue('jenis_kelamin');
            const jkLabel = jk === 'L' ? 'Laki-laki' : jk === 'P' ? 'Perempuan' : '—';
            const metode = fieldValue('metode_pembayaran');
            const metodeLabel = metode === 'transfer' ? 'Transfer bank' : metode === 'bayar_ditempat' ? 'Bayar di tempat' : '—';

            return [
                { label: 'Data Diri', ok: Boolean(fieldValue('nama_lengkap') && fieldValue('tanggal_lahir')), detail: fieldValue('nama_lengkap') || 'Belum lengkap' },
                { label: 'Alamat', ok: Boolean(fieldValue('provinsi_id') && fieldValue('alamat')), detail: fieldValue('desa') || fieldValue('alamat') || 'Belum lengkap' },
                { label: 'Lembaga', ok: Boolean(fieldValue('lembaga')), detail: fieldValue('lembaga') || 'Belum diisi' },
                { label: 'Dokumen', ok: Boolean(this.files.pas_foto), detail: this.files.pas_foto ? `Pas foto: ${this.files.pas_foto}` : 'Pas foto belum diunggah' },
                {
                    label: 'Pembayaran',
                    ok: Boolean(metode) && (metode !== 'transfer' || this.files.bukti_transfer),
                    detail: metodeLabel + (this.files.bukti_transfer ? ` · Bukti: ${this.files.bukti_transfer}` : metode === 'transfer' ? ' · Bukti belum diunggah' : ''),
                },
                { label: 'Kontak', ok: true, detail: [fieldValue('no_wa'), fieldValue('email')].filter(Boolean).join(' · ') || 'Opsional' },
            ];
        },

        handleSubmit(event) {
            this.stepError = '';
            for (let s = 1; s <= 5; s += 1) {
                if (!validatePanel(s)) {
                    event.preventDefault();
                    this.step = s;
                    return;
                }
            }

            if (!this.declaration) {
                event.preventDefault();
                this.stepError = 'Centang pernyataan kebenaran data sebelum mengirim.';
                this.step = 6;
            }
        },
    }));
});

document.addEventListener('DOMContentLoaded', syncPaymentVisibility);
window.syncPaymentVisibility = syncPaymentVisibility;
