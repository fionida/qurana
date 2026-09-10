@extends('layouts.public')

@section('title', 'Form Pendaftaran')
@section('containerClass', 'max-w-4xl')

@push('scripts')
    @vite(['resources/js/registration.js'])
@endpush

@section('content')
<div class="public-card overflow-hidden">
    @include('registration.partials.hero')

    @if (empty($gelombang))
        <x-admin.alert type="error" class="mx-6 mt-6">Pendaftaran sedang ditutup atau kuota penuh. Hubungi panitia.</x-admin.alert>
    @endif
    @if (session('error'))
        <x-admin.alert type="error" class="mx-6 mt-4">{{ session('error') }}</x-admin.alert>
    @endif

    <form id="registration-form"
        action="{{ route('registration.store', $program) }}"
        method="POST"
        enctype="multipart/form-data"
        novalidate
        @if(empty($gelombang)) onsubmit="return false;" @endif
        x-data="registrationWizard(@js(['initialStep' => $wizardInitialStep, 'files' => []]))"
        @submit="handleSubmit($event)"
    >
        @csrf

        <nav class="reg-stepper" aria-label="Langkah pendaftaran">
            <template x-for="item in steps" :key="item.num">
                <button type="button" class="reg-stepper-item" @click="goToStep(item.num)" :disabled="item.num > step">
                    <span class="reg-stepper-dot"
                        :class="{
                            'reg-stepper-dot-active': isActive(item.num),
                            'reg-stepper-dot-done': isDone(item.num)
                        }"
                        x-text="item.num"></span>
                    <span class="reg-stepper-label"
                        :class="{ 'reg-stepper-label-active': isActive(item.num) }"
                        x-text="item.label"></span>
                </button>
            </template>
        </nav>

        @include('registration.partials.step-data-diri')
        @include('registration.partials.step-alamat')
        @include('registration.partials.step-lembaga')
        @include('registration.partials.step-dokumen')
        @include('registration.partials.step-pembayaran')
        @include('registration.partials.step-review')

        <div class="reg-nav">
            <button type="button" class="public-btn-secondary w-full sm:w-auto" x-show="step > 1" @click="goPrev()">Sebelumnya</button>
            <div class="flex-1"></div>
            <button type="button" class="public-btn-primary w-full sm:w-auto sm:min-w-[140px]" x-show="step < 6" @click="goNext()">Selanjutnya</button>
            <button type="submit" class="public-btn-primary w-full !py-3 sm:w-auto sm:px-10" x-show="step === 6" x-cloak>
                Kirim Pendaftaran
            </button>
        </div>
    </form>
</div>
@endsection
