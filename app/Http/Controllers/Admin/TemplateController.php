<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Support\DocumentTemplate;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class TemplateController extends Controller
{
    public function index(): View
    {
        $rows = [];

        foreach (DocumentTemplate::slots() as $slot => $meta) {
            $rows[] = [
                'slot' => $slot,
                'meta' => $meta,
                'original_name' => DocumentTemplate::originalName($slot),
                'kind' => DocumentTemplate::kind($slot),
                'preview_url' => DocumentTemplate::kind($slot) === 'image' ? DocumentTemplate::publicUrl($slot) : null,
                'download_url' => DocumentTemplate::path($slot)
                    ? route('admin.templates.download', $slot)
                    : null,
            ];
        }

        return view('admin.templates.index', ['rows' => $rows]);
    }

    public function update(Request $request, string $slot): RedirectResponse
    {
        abort_unless(DocumentTemplate::slotExists($slot), 404);

        $meta = DocumentTemplate::slots()[$slot];
        $maxKb = (int) ($meta['max_kb'] ?? 10240);

        $request->validate([
            'file' => ['required', 'file', 'mimes:'.($meta['mimes'] ?? 'png'), 'max:'.$maxKb],
        ], [
            'file.required' => 'Pilih berkas template.',
            'file.mimes' => 'Format berkas tidak didukung untuk jenis template ini.',
        ]);

        DocumentTemplate::storeUpload($slot, $request->file('file'));

        return redirect()
            ->route('admin.templates.index')
            ->with('success', 'Template «'.($meta['label'] ?? $slot).'» berhasil diunggah.');
    }

    public function destroy(string $slot): RedirectResponse
    {
        abort_unless(DocumentTemplate::slotExists($slot), 404);

        $label = DocumentTemplate::slots()[$slot]['label'] ?? $slot;
        DocumentTemplate::delete($slot);

        return redirect()
            ->route('admin.templates.index')
            ->with('success', 'Template «'.$label.'» dihapus.');
    }

    public function download(string $slot): Response|StreamedResponse
    {
        abort_unless(DocumentTemplate::slotExists($slot), 404);

        $relative = DocumentTemplate::path($slot);
        abort_unless($relative && Storage::disk('public')->exists($relative), 404);

        $name = DocumentTemplate::originalName($slot) ?? basename($relative);

        return Storage::disk('public')->download($relative, $name);
    }
}
