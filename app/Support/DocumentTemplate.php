<?php

namespace App\Support;

use App\Models\Setting;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class DocumentTemplate
{
    public static function slots(): array
    {
        return config('document_templates.slots', []);
    }

    public static function slotExists(string $slot): bool
    {
        return array_key_exists($slot, self::slots());
    }

    public static function path(string $slot): ?string
    {
        if (! self::slotExists($slot)) {
            return null;
        }

        $path = Setting::get(self::pathKey($slot));

        return $path !== null && $path !== '' ? $path : null;
    }

    public static function originalName(string $slot): ?string
    {
        $name = Setting::get(self::nameKey($slot));

        return $name !== null && $name !== '' ? $name : null;
    }

    public static function absolutePath(string $slot): ?string
    {
        $relative = self::path($slot);
        if (! $relative) {
            return null;
        }

        foreach ([
            storage_path('app/public/'.$relative),
            public_path('storage/'.$relative),
        ] as $absolute) {
            if (is_file($absolute)) {
                return $absolute;
            }
        }

        return null;
    }

    public static function publicUrl(string $slot): ?string
    {
        $relative = self::path($slot);
        if (! $relative) {
            return null;
        }

        return self::absolutePath($slot) ? '/storage/'.ltrim(str_replace('\\', '/', $relative), '/') : null;
    }

    /**
     * @return 'image'|'pdf'|'word'|null
     */
    public static function kind(string $slot): ?string
    {
        $absolute = self::absolutePath($slot);
        if (! $absolute) {
            return null;
        }

        $ext = strtolower(pathinfo($absolute, PATHINFO_EXTENSION));

        return match ($ext) {
            'jpg', 'jpeg', 'png' => 'image',
            'pdf' => 'pdf',
            'doc', 'docx' => 'word',
            default => null,
        };
    }

    public static function isPrintableBackground(string $slot): bool
    {
        return self::kind($slot) === 'image';
    }

    public static function storeUpload(string $slot, UploadedFile $file): void
    {
        $old = self::path($slot);
        if ($old) {
            Storage::disk('public')->delete($old);
        }

        $dir = config('document_templates.storage_directory', 'document-templates');
        $stored = $file->store($dir.'/'.$slot, 'public');

        Setting::set(self::pathKey($slot), $stored);
        Setting::set(self::nameKey($slot), $file->getClientOriginalName());
    }

    public static function delete(string $slot): void
    {
        $old = self::path($slot);
        if ($old) {
            Storage::disk('public')->delete($old);
        }

        Setting::set(self::pathKey($slot), '');
        Setting::set(self::nameKey($slot), '');
    }

    public static function pathKey(string $slot): string
    {
        return 'document_template_'.$slot.'_path';
    }

    public static function nameKey(string $slot): string
    {
        return 'document_template_'.$slot.'_name';
    }
}
