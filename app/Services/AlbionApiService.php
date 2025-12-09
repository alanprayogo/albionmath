<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class AlbionApiService
{
    protected string $baseUrl = 'https://api.openalbion.com/api/v3';

    /**
     * Ambil daftar kategori dari API (dengan caching 1 jam)
     */
    public function getCategories(): array
    {
        return Cache::remember('albion_categories', now()->addHour(), function () {
            $response = Http::timeout(10)->get("{$this->baseUrl}/categories");

            if ($response->failed()) {
                // Jika API error, return data kosong agar tidak crash
                return [];
            }

            $data = $response->json();

            // Pastikan format sesuai: ada key "data"
            return $data['data'] ?? [];
        });
    }

    /**
     * Ekstrak daftar unik "type" dari semua kategori & subkategori
     */
    public function getCategoryTypes(): array
    {
        $categories = $this->getCategories();
        $types = [];

        foreach ($categories as $category) {
            // Tambahkan type dari kategori utama
            if (!empty($category['type'])) {
                $types[] = $category['type'];
            }

            // Tambahkan type dari subkategori
            if (!empty($category['subcategories'])) {
                foreach ($category['subcategories'] as $sub) {
                    if (!empty($sub['type'])) {
                        $types[] = $sub['type'];
                    }
                }
            }
        }

        // Hapus duplikat dan reset index
        return array_values(array_unique($types));
    }

    /**
     * Ambil daftar kategori utama (bukan subkategori) berdasarkan type
     */
    public function getMainCategoriesByType(string $type): array
    {
        $categories = $this->getCategories();

        return collect($categories)
            ->filter(fn($cat) => ($cat['type'] ?? null) === $type)
            ->values()
            ->all();
    }

    /**
     * Ambil daftar subkategori berdasarkan ID kategori utama
     */
    public function getSubcategoriesByCategoryId(int $categoryId): array
    {
        $categories = $this->getCategories();

        $category = collect($categories)
            ->first(fn($cat) => ($cat['id'] ?? null) === $categoryId);

        return $category['subcategories'] ?? [];
    }
}