@extends('layouts.admin')

@section('title', 'Manajemen User')

@section('content')
@php
    $editData = [
        'id' => old('_user_id', $editUser?->id),
        'name' => old('name', $editUser?->name ?? ''),
        'email' => old('email', $editUser?->email ?? ''),
    ];
@endphp

<div class="admin-page" x-data="{
    createOpen: {{ ($openCreate ?? false) ? 'true' : 'false' }},
    editOpen: {{ ($openEdit ?? false) ? 'true' : 'false' }},
    editForm: @js($editData),
    openEdit(user) {
        this.editForm = user;
        this.editOpen = true;
    }
}">
    <x-admin.page-header title="Manajemen User" description="Kelola akun admin panel Qurana">
        <x-slot:actions>
            <button type="button" @click="createOpen = true" class="admin-btn-primary">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                Tambah User
            </button>
        </x-slot:actions>
    </x-admin.page-header>

    <div class="admin-page-toolbar">
        <div class="admin-card">
            <div class="admin-card-body !py-4">
                <form method="GET" class="flex flex-col gap-3 sm:flex-row">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau email..." class="admin-input flex-1">
                    <button type="submit" class="admin-btn-primary sm:w-auto">Cari</button>
                </form>
            </div>
        </div>
    </div>

    <div class="admin-page-body">
        <div class="admin-card">
            <div class="admin-table-wrap">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Terdaftar</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $user)
                            <tr>
                                <td>
                                    <div class="flex items-center gap-2">
                                        <span class="font-medium text-slate-900">{{ $user->name }}</span>
                                        @if ($user->id === auth()->id())
                                            <span class="admin-badge-success">Anda</span>
                                        @endif
                                    </div>
                                </td>
                                <td>{{ $user->email }}</td>
                                <td class="text-slate-500">{{ $user->created_at->format('d/m/Y') }}</td>
                                <td>
                                    <div class="flex gap-3">
                                        <button type="button"
                                            @click="openEdit({ id: {{ $user->id }}, name: @js($user->name), email: @js($user->email) })"
                                            class="text-sm font-medium text-emerald-600 hover:text-emerald-700">Edit</button>
                                        @if ($user->id !== auth()->id())
                                            <form x-ref="deleteForm{{ $user->id }}" action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline">
                                                @csrf @method('DELETE')
                                                <button type="button"
                                                    @click="askConfirm(@js('Yakin hapus user '.$user->name.'?'), $refs.deleteForm{{ $user->id }}, { title: 'Hapus User', confirmText: 'Ya, hapus', variant: 'danger' })"
                                                    class="text-sm font-medium text-red-600 hover:text-red-700">Hapus</button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="py-16 text-center text-slate-400">Belum ada user admin</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($users->hasPages())
                <div class="shrink-0 border-t border-slate-100 px-5 py-4">{{ $users->links() }}</div>
            @endif
        </div>
    </div>

    <x-admin.modal show="createOpen" title="Tambah User Admin">
        <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-4">
            @csrf
            <input type="hidden" name="_modal" value="create">
            <div>
                <label class="admin-label">Nama</label>
                <input type="text" name="name" value="{{ old('_modal') === 'create' ? old('name') : '' }}" required class="admin-input">
                @if (old('_modal') === 'create') @error('name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror @endif
            </div>
            <div>
                <label class="admin-label">Email</label>
                <input type="email" name="email" value="{{ old('_modal') === 'create' ? old('email') : '' }}" required class="admin-input">
                @if (old('_modal') === 'create') @error('email')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror @endif
            </div>
            <div>
                <label class="admin-label">Password</label>
                <input type="password" name="password" required class="admin-input" autocomplete="new-password">
                @if (old('_modal') === 'create') @error('password')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror @endif
            </div>
            <div>
                <label class="admin-label">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" required class="admin-input" autocomplete="new-password">
            </div>
            <div class="flex gap-3 pt-2">
                <button type="button" @click="createOpen = false" class="admin-btn-secondary flex-1">Batal</button>
                <button type="submit" class="admin-btn-primary flex-1">Simpan</button>
            </div>
        </form>
    </x-admin.modal>

    <x-admin.modal show="editOpen" title="Edit User Admin">
        <form :action="`/admin/users/${editForm.id}`" method="POST" class="space-y-4">
            @csrf @method('PUT')
            <input type="hidden" name="_modal" value="edit">
            <input type="hidden" name="_user_id" :value="editForm.id">
            <div>
                <label class="admin-label">Nama</label>
                <input type="text" name="name" x-model="editForm.name" required class="admin-input">
                @if (old('_modal') === 'edit') @error('name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror @endif
            </div>
            <div>
                <label class="admin-label">Email</label>
                <input type="email" name="email" x-model="editForm.email" required class="admin-input">
                @if (old('_modal') === 'edit') @error('email')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror @endif
            </div>
            <div>
                <label class="admin-label">Password Baru</label>
                <input type="password" name="password" class="admin-input" autocomplete="new-password">
                <p class="mt-1 text-xs text-slate-400">Kosongkan jika tidak ingin mengubah password</p>
                @if (old('_modal') === 'edit') @error('password')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror @endif
            </div>
            <div>
                <label class="admin-label">Konfirmasi Password Baru</label>
                <input type="password" name="password_confirmation" class="admin-input" autocomplete="new-password">
            </div>
            <div class="flex gap-3 pt-2">
                <button type="button" @click="editOpen = false" class="admin-btn-secondary flex-1">Batal</button>
                <button type="submit" class="admin-btn-primary flex-1">Perbarui</button>
            </div>
        </form>
    </x-admin.modal>
</div>
@endsection
