<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class AlbionApiService
{
    protected string $baseUrl = 'https://api.openalbion.com/api/v3';

    // Mapping antara "type" di kategori dan endpoint API
    protected array $typeToEndpoint = [
        'weapon'    => 'weapons',
        'armor'     => 'armors',
        'accessory' => 'accessories',
        'consumable'=> 'consumables',
        // tambahkan lainnya jika diperlukan
    ];

    public function getCategories(): array
    {
        return Cache::remember('albion_categories', now()->addHour(), function () {
            $response = Http::timeout(10)->get("{$this->baseUrl}/categories");
            return $response->successful() ? ($response->json()['data'] ?? []) : [];
        });
    }

    public function getCategoryTypes(): array
    {
        $categories = $this->getCategories();
        $types = [];

        foreach ($categories as $category) {
            if (!empty($category['type'])) {
                $types[] = $category['type'];
            }
            if (!empty($category['subcategories'])) {
                foreach ($category['subcategories'] as $sub) {
                    if (!empty($sub['type'])) {
                        $types[] = $sub['type'];
                    }
                }
            }
        }

        return array_values(array_unique($types));
    }

    public function getMainCategoriesByType(string $type): array
    {
        $categories = $this->getCategories();
        return collect($categories)
            ->filter(fn($cat) => ($cat['type'] ?? null) === $type)
            ->values()
            ->all();
    }

    public function getSubcategoriesByCategoryId(int $categoryId): array
    {
        $categories = $this->getCategories();
        $category = collect($categories)->first(fn($cat) => ($cat['id'] ?? null) === $categoryId);
        return $category['subcategories'] ?? [];
    }

    /**
     * Ambil item berdasarkan type (weapon, armor, dll.)
     */
    public function getItemsByType(string $type): array
    {
        if (!isset($this->typeToEndpoint[$type])) {
            return [];
        }

        $endpoint = $this->typeToEndpoint[$type];
        return Cache::remember("albion_items_{$type}", now()->addHour(), function () use ($endpoint) {
            $response = Http::timeout(10)->get("{$this->baseUrl}/{$endpoint}");
            return $response->successful() ? ($response->json()['data'] ?? []) : [];
        });
    }
}