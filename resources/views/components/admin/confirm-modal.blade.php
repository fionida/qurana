<div x-show="confirm.open" x-cloak class="fixed inset-0 z-[70] flex items-center justify-center p-4"
    @keydown.escape.window="cancelConfirm()">
    <div x-show="confirm.open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
        class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" @click="cancelConfirm()"></div>

    <div x-show="confirm.open" x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95 translate-y-2"
        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 scale-100 translate-y-0"
        x-transition:leave-end="opacity-0 scale-95 translate-y-2"
        class="relative w-full max-w-sm overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-2xl"
        @click.stop>
        <div class="border-b border-slate-100 px-6 py-5">
            <div class="flex items-start gap-4">
                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl"
                    :class="confirm.variant === 'danger' ? 'bg-red-50 text-red-600' : 'bg-emerald-50 text-emerald-600'">
                    <svg x-show="confirm.variant !== 'danger'" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                    <svg x-show="confirm.variant === 'danger'" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-slate-900" x-text="confirm.title"></h3>
                    <p class="mt-2 text-sm leading-relaxed text-slate-600" x-text="confirm.message"></p>
                </div>
            </div>
        </div>
        <div class="flex gap-3 px-6 py-4">
            <button type="button" @click="cancelConfirm()" class="admin-btn-secondary flex-1" x-text="confirm.cancelText"></button>
            <button type="button" @click="submitConfirm()" class="flex-1 rounded-lg px-4 py-2.5 text-sm font-semibold text-white transition focus:outline-none focus:ring-2 focus:ring-offset-2"
                :class="confirm.variant === 'danger' ? 'bg-red-600 hover:bg-red-700 focus:ring-red-500' : 'bg-emerald-600 hover:bg-emerald-700 focus:ring-emerald-500'"
                x-text="confirm.confirmText"></button>
        </div>
    </div>
</div>
