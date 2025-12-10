<?php

namespace App\Services;

use Illuminate\Support\Facades\File;

class CraftingService
{
    public function getRecipe(string $type, int $itemId): ?array
    {
        $key = "{$type}/{$itemId}";
        $path = storage_path('app/data/crafting.json');

        if (!File::exists($path)) {
            return null;
        }

        $data = json_decode(File::get($path), true);
        return $data[$key] ?? null;
    }

    public function getVariant(array $recipe, string $enchant = '0'): ?array
    {
        return $recipe['variants'][$enchant] ?? ($recipe['variants']['0'] ?? null);
    }
}