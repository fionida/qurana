import flatpickr from 'flatpickr';
import { Indonesian } from 'flatpickr/dist/l10n/id.js';
import 'flatpickr/dist/flatpickr.min.css';

document.addEventListener('DOMContentLoaded', () => {
    const wrap = document.getElementById('tanggal_lahir_wrap');
    if (wrap) {
        flatpickr(wrap, {
            locale: Indonesian,
            wrap: true,
            dateFormat: 'Y-m-d',
            altInput: true,
            altFormat: 'd F Y',
            maxDate: 'today',
            disableMobile: true,
            animate: true,
            monthSelectorType: 'dropdown',
            prevArrow: '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-4 w-4"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" /></svg>',
            nextArrow: '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-4 w-4"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" /></svg>',
        });
    }

    const namaInput = document.getElementById('nama_lengkap');
    if (namaInput) {
        const toUpper = () => {
            const start = namaInput.selectionStart;
            const end = namaInput.selectionEnd;
            namaInput.value = namaInput.value.toUpperCase();
            namaInput.setSelectionRange(start, end);
        };
        namaInput.addEventListener('input', toUpper);
        toUpper();
    }

    initWilayahCascade();
});

function togglePayment() {
    const isTransfer = document.querySelector('input[name="metode_pembayaran"][value="transfer"]')?.checked;
    document.getElementById('bukti-transfer-field')?.classList.toggle('hidden', !isTransfer);
}

document.addEventListener('DOMContentLoaded', togglePayment);
window.togglePayment = togglePayment;

async function initWilayahCascade() {
    const provinsiSelect = document.getElementById('provinsi_id');
    if (!provinsiSelect) return;

    const regencySelect = document.getElementById('kota_kab_id');
    const districtSelect = document.getElementById('kecamatan_id');
    const villageSelect = document.getElementById('desa_id');

    const provinsiName = document.getElementById('provinsi');
    const regencyName = document.getElementById('kota_kab');
    const districtName = document.getElementById('kecamatan');
    const villageName = document.getElementById('desa');

    const form = document.getElementById('registration-form');
    const wilayahSelects = [provinsiSelect, regencySelect, districtSelect, villageSelect];
    const wilayahPairs = [
        [provinsiSelect, provinsiName],
        [regencySelect, regencyName],
        [districtSelect, districtName],
        [villageSelect, villageName],
    ];

    const oldEl = document.getElementById('wilayah-old');
    const oldValues = oldEl ? JSON.parse(oldEl.textContent || '{}') : {};

    const fillSelect = (select, items, placeholder, selectedId = '') => {
        select.innerHTML = `<option value="">${placeholder}</option>`;
        items.forEach((item) => {
            const opt = document.createElement('option');
            opt.value = item.id;
            opt.textContent = item.name;
            if (String(item.id) === String(selectedId)) {
                opt.selected = true;
            }
            select.appendChild(opt);
        });
    };

    const fetchJson = async (url) => {
        const res = await fetch(url);
        if (!res.ok) return [];
        return res.json();
    };

    const syncHiddenName = (select, hiddenInput) => {
        if (!hiddenInput) return;
        const selected = select.options[select.selectedIndex];
        hiddenInput.value = selected && selected.value ? selected.textContent.trim() : '';
    };

    const syncAllWilayahFields = () => {
        wilayahPairs.forEach(([select, hidden]) => syncHiddenName(select, hidden));
    };

    const enableWilayahSelectsForSubmit = () => {
        wilayahSelects.forEach((select) => {
            if (select.value) {
                select.disabled = false;
            }
        });
    };

    if (form) {
        form.addEventListener('submit', () => {
            syncAllWilayahFields();
            enableWilayahSelectsForSubmit();
        });
    }

    provinsiSelect.addEventListener('change', async () => {
        syncHiddenName(provinsiSelect, provinsiName);
        fillSelect(regencySelect, [], '— Pilih Kota/Kab —');
        fillSelect(districtSelect, [], '— Pilih Kecamatan —');
        fillSelect(villageSelect, [], '— Pilih Desa/Kel —');
        regencyName.value = '';
        districtName.value = '';
        villageName.value = '';
        regencySelect.disabled = true;
        districtSelect.disabled = true;
        villageSelect.disabled = true;

        if (!provinsiSelect.value) return;

        regencySelect.disabled = false;
        const regencies = await fetchJson(`/api/wilayah/regencies/${provinsiSelect.value}`);
        fillSelect(regencySelect, regencies, '— Pilih Kota/Kab —');
    });

    regencySelect.addEventListener('change', async () => {
        syncHiddenName(regencySelect, regencyName);
        fillSelect(districtSelect, [], '— Pilih Kecamatan —');
        fillSelect(villageSelect, [], '— Pilih Desa/Kel —');
        districtName.value = '';
        villageName.value = '';
        districtSelect.disabled = true;
        villageSelect.disabled = true;

        if (!regencySelect.value) return;

        districtSelect.disabled = false;
        const districts = await fetchJson(`/api/wilayah/districts/${regencySelect.value}`);
        fillSelect(districtSelect, districts, '— Pilih Kecamatan —');
    });

    districtSelect.addEventListener('change', async () => {
        syncHiddenName(districtSelect, districtName);
        fillSelect(villageSelect, [], '— Pilih Desa/Kel —');
        villageName.value = '';
        villageSelect.disabled = true;

        if (!districtSelect.value) return;

        villageSelect.disabled = false;
        const villages = await fetchJson(`/api/wilayah/villages/${districtSelect.value}`);
        fillSelect(villageSelect, villages, '— Pilih Desa/Kel —');
    });

    villageSelect.addEventListener('change', () => {
        syncHiddenName(villageSelect, villageName);
    });

    const provinces = await fetchJson('/api/wilayah/provinces');
    fillSelect(provinsiSelect, provinces, '— Pilih Provinsi —', oldValues.provinsi_id || '');

    if (oldValues.provinsi_id) {
        syncHiddenName(provinsiSelect, provinsiName);
        if (oldValues.provinsi) provinsiName.value = oldValues.provinsi;
        regencySelect.disabled = false;
        const regencies = await fetchJson(`/api/wilayah/regencies/${oldValues.provinsi_id}`);
        fillSelect(regencySelect, regencies, '— Pilih Kota/Kab —', oldValues.kota_kab_id || '');
        if (oldValues.kota_kab) regencyName.value = oldValues.kota_kab;
    }

    if (oldValues.kota_kab_id) {
        districtSelect.disabled = false;
        const districts = await fetchJson(`/api/wilayah/districts/${oldValues.kota_kab_id}`);
        fillSelect(districtSelect, districts, '— Pilih Kecamatan —', oldValues.kecamatan_id || '');
        if (oldValues.kecamatan) districtName.value = oldValues.kecamatan;
    }

    if (oldValues.kecamatan_id) {
        villageSelect.disabled = false;
        const villages = await fetchJson(`/api/wilayah/villages/${oldValues.kecamatan_id}`);
        fillSelect(villageSelect, villages, '— Pilih Desa/Kel —', oldValues.desa_id || '');
        if (oldValues.desa) villageName.value = oldValues.desa;
    }
}
