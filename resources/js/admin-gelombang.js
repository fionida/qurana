/**
 * Saran tanggal Hijriyah untuk form gelombang (admin).
 */
export async function suggestHijriForForm(formPrefix, suggestUrl) {
    const masehi = document.getElementById(`${formPrefix}_tanggal_terbit_masehi`)?.value;
    const hijriInput = document.getElementById(`${formPrefix}_tanggal_terbit_hijriyah`);

    if (!masehi || !hijriInput || hijriInput.value) {
        return;
    }

    try {
        const res = await fetch(`${suggestUrl}?date=${encodeURIComponent(masehi)}`, {
            headers: {
                Accept: 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
            },
        });
        const data = await res.json();

        if (data.hijriyah) {
            hijriInput.value = data.hijriyah;
        }
    } catch {
        // abaikan — admin bisa isi manual
    }
}

document.addEventListener('alpine:init', () => {
    const Alpine = window.Alpine;

    if (!Alpine) {
        return;
    }

    Alpine.data('gelombangIndexPage', (config) => ({
        createOpen: config.createOpen,
        editOpen: config.editOpen,
        editForm: config.editForm,
        suggestHijriUrl: config.suggestHijriUrl,
        openEdit(g) {
            this.editForm = g;
            this.editOpen = true;
        },
        suggestHijri(formPrefix) {
            suggestHijriForForm(formPrefix, this.suggestHijriUrl);
        },
    }));
});
