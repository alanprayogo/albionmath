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
        if (empty($this->type)) {
            return [];
        }

        $service = new AlbionApiService();
        $items = $service->getItemsByType($this->type);

        // Filter berdasarkan category
        if (!empty($this->category)) {
            $items = collect($items)->filter(fn($item) => 
                ($item['category']['id'] ?? null) == $this->category
            )->all();
        }

        // Filter berdasarkan subcategory
        if (!empty($this->subcategory)) {
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

        // Filter berdasarkan search
        if (!empty($this->search)) {
            $search = strtolower($this->search);
            $items = collect($items)->filter(fn($item) => 
                str_contains(strtolower($item['name'] ?? ''), $search)
            )->all();
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