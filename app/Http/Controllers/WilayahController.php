<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class WilayahController extends Controller
{
    private const BASE_URL = 'https://www.emsifa.com/api-wilayah-indonesia/api';

    public function provinces(): JsonResponse
    {
        return response()->json($this->fetch('provinces.json'));
    }

    public function regencies(string $provinceId): JsonResponse
    {
        return response()->json($this->fetch("regencies/{$provinceId}.json"));
    }

    public function districts(string $regencyId): JsonResponse
    {
        return response()->json($this->fetch("districts/{$regencyId}.json"));
    }

    public function villages(string $districtId): JsonResponse
    {
        return response()->json($this->fetch("villages/{$districtId}.json"));
    }

    private function fetch(string $path): array
    {
        return Cache::remember("wilayah.{$path}", now()->addDays(7), function () use ($path) {
            $response = Http::timeout(15)->get(self::BASE_URL.'/'.$path);

            return $response->successful() ? $response->json() : [];
        });
    }
}
