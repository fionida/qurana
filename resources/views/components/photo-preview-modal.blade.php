<div id="photo-modal" class="fixed inset-0 z-[60] hidden" aria-hidden="true">
    <div class="absolute inset-0 bg-slate-900/70 backdrop-blur-sm" onclick="closePhotoModal()"></div>
    <div class="absolute inset-0 flex items-center justify-center p-4">
        <div class="w-full max-w-md overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-2xl">
            <div class="flex items-center justify-between border-b border-slate-100 px-5 py-4">
                <h3 id="photo-modal-title" class="font-semibold text-slate-900">Preview Pas Foto</h3>
                <button type="button" onclick="closePhotoModal()" class="rounded-lg p-1.5 text-slate-400 hover:bg-slate-100 hover:text-slate-600">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" /></svg>
                </button>
            </div>
            <div class="p-5">
                <img id="photo-modal-image" src="" alt="Pas Foto Santri" class="w-full rounded-xl border border-slate-200">
            </div>
        </div>
    </div>
</div>

<script>
    function openPhotoModal(url, name) {
        document.getElementById('photo-modal-image').src = url;
        document.getElementById('photo-modal-title').textContent = 'Pas Foto — ' + name;
        document.getElementById('photo-modal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }
    function closePhotoModal() {
        document.getElementById('photo-modal').classList.add('hidden');
        document.getElementById('photo-modal-image').src = '';
        document.body.style.overflow = '';
    }
    document.addEventListener('keydown', e => { if (e.key === 'Escape') closePhotoModal(); });
</script>
