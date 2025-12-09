<?php

namespace App\Livewire;

use App\Services\AlbionApiService;
use Livewire\Component;

class ItemList extends Component
{
    public $search = '';
    public $type = '';
    public $category = '';
    public $subcategory = '';
    public $tier = '';
    public $quality = '';
    public $enhancement = '';

    public function getTypeOptionsProperty()
    {
        $service = new AlbionApiService();
        return $service->getCategoryTypes();
    }

    public function getCategoryOptionsProperty()
    {
        if (empty($this->type)) return [];
        $service = new AlbionApiService();
        return $service->getMainCategoriesByType($this->type);
    }

    public function getSubcategoryOptionsProperty()
    {
        if (empty($this->category) || !is_numeric($this->category)) return [];
        $service = new AlbionApiService();
        return $service->getSubcategoriesByCategoryId((int) $this->category);
    }

    public function getFilteredItemsProperty()
    {
        $service = new AlbionApiService();

        // Jika type dipilih, ambil hanya dari tipe tersebut
        if (!empty($this->type)) {
            $items = $service->getItemsByType($this->type);
        } else {
            // Jika tidak, ambil SEMUA item
            $items = $service->getAllItems();
        }

        // Filter berdasarkan category (hanya jika type dipilih)
        if (!empty($this->type) && !empty($this->category)) {
            $items = collect($items)->filter(fn($item) => 
                ($item['category']['id'] ?? null) == $this->category
            )->all();
        }

        // Filter berdasarkan subcategory (hanya jika type dipilih)
        if (!empty($this->type) && !empty($this->subcategory)) {
            $items = collect($items)->filter(fn($item) => 
                ($item['subcategory']['id'] ?? null) == $this->subcategory
            )->all();
        }

        // Filter berdasarkan tier
        if (!empty($this->tier)) {
            $items = collect($items)->filter(fn($item) => 
                $item['tier'] == $this->tier
            )->all();
        }

        // Filter berdasarkan search (berlaku untuk semua kasus)
        if (!empty($this->search)) {
            $searchTerm = strtolower(trim($this->search));
            $items = collect($items)->filter(function ($item) use ($searchTerm) {
                $name = strtolower($item['name'] ?? '');
                return str_contains($name, $searchTerm);
            })->all();
        }

        return $items;
    }

    public function render()
    {
        return view('livewire.item-list', [
            'typeOptions' => $this->typeOptions,
            'categoryOptions' => $this->categoryOptions,
            'subcategoryOptions' => $this->subcategoryOptions,
            'items' => $this->filteredItems,
        ]);
    }
}