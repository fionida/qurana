<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class BrandingController extends Controller
{
    public function edit(): View
    {
        return view('admin.branding.edit', [
            'logoUrl' => Setting::logoUrl(),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'logo' => ['required', 'image', 'mimes:jpeg,jpg,png,svg,webp', 'max:2048'],
        ], [
            'logo.required' => 'Logo wajib diunggah.',
            'logo.image' => 'Logo harus berupa gambar.',
            'logo.max' => 'Logo maksimal 2 MB.',
        ]);

        $oldLogo = Setting::logoPath();
        if ($oldLogo) {
            Storage::disk('public')->delete($oldLogo);
        }

        $path = $request->file('logo')->store('branding', 'public');
        Setting::set('site_logo', $path);

        return back()->with('success', 'Logo berhasil diperbarui.');
    }

    public function destroy(): RedirectResponse
    {
        $oldLogo = Setting::logoPath();
        if ($oldLogo) {
            Storage::disk('public')->delete($oldLogo);
            Setting::set('site_logo', '');
        }

        return back()->with('success', 'Logo dihapus, kembali ke logo default.');
    }
}
