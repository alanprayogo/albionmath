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

    /**
     * Ambil dan flatten semua varian (quality + enchantment) untuk item tertentu
     */
    public function getItemStats(string $type, int $itemId): array
    {
        $typeMap = [
            'weapon' => 'weapon',
            'armor' => 'armor',
            'accessory' => 'accessory',
            'consumable' => 'consumable',
        ];

        if (!isset($typeMap[$type])) {
            return [];
        }

        $path = $typeMap[$type];
        $url = "{$this->baseUrl}/{$type}-stats/{$path}/{$itemId}";

        return Cache::remember("albion_item_stats_{$type}_{$itemId}", now()->addHour(), function () use ($url, $type) {
            $response = Http::timeout(10)->get($url);

            if (!$response->successful()) {
                return [];
            }

            $responseData = $response->json();
            $rawData = $responseData['data'] ?? [];

            $flattened = [];

            foreach ($rawData as $enchantGroup) {
                $enchantment = $enchantGroup['enchantment'] ?? 0;
                $baseIcon = $enchantGroup['icon'] ?? '';

                if (!empty($enchantGroup['stats'])) {
                    foreach ($enchantGroup['stats'] as $qualityStat) {
                        // 🔥 Ambil data item dari key yang sesuai tipe
                        $itemKey = $type; // 'weapon', 'armor', dll.
                        $itemData = $qualityStat[$itemKey] ?? [];

                        $flattened[] = [
                            'enchantment' => $enchantment,
                            'quality' => $qualityStat['quality'] ?? 'Normal',
                            'item' => $itemData, // ← gunakan 'item' sebagai key umum
                            'stats' => $qualityStat['stats'] ?? [],
                            'icon' => $itemData['icon'] ?? $baseIcon,
                            'type' => $type,
                        ];
                    }
                }
            }

            return $flattened;
        });
    }

    /**
     * Ambil data dasar satu item berdasarkan type dan ID
     */
    public function getBaseItem(string $type, int $itemId): ?array
    {
        if (!isset($this->typeToEndpoint[$type])) {
            return null;
        }

        $endpoint = $this->typeToEndpoint[$type];
        $url = "{$this->baseUrl}/{$endpoint}";

        $items = Cache::remember("albion_items_{$type}", now()->addHour(), function () use ($url) {
            $response = Http::timeout(10)->get($url);
            return $response->successful() ? ($response->json()['data'] ?? []) : [];
        });

        // Cari item dengan ID yang cocok
        foreach ($items as $item) {
            if (($item['id'] ?? null) == $itemId) {
                return $item;
            }
        }

        return null;
    }
}