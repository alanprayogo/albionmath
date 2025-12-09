<?php

namespace App\Livewire;

use App\Services\AlbionApiService;
use Livewire\Component;

class CraftingItemList extends Component
{
    public $search = '';
    public $type = '';
    public $category = '';
    public $subcategory = '';
    public $tier = '';

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

        if (!empty($this->type)) {
            $items = $service->getItemsByType($this->type);
        } else {
            $items = $service->getAllItems();
        }

        if (!empty($this->type) && !empty($this->category)) {
            $items = collect($items)->filter(fn($item) => 
                ($item['category']['id'] ?? null) == $this->category
            )->all();
        }

        if (!empty($this->type) && !empty($this->subcategory)) {
            $items = collect($items)->filter(fn($item) => 
                ($item['subcategory']['id'] ?? null) == $this->subcategory
            )->all();
        }

        if (!empty($this->tier)) {
            $items = collect($items)->filter(fn($item) => 
                $item['tier'] == $this->tier
            )->all();
        }

        if (!empty($this->search)) {
            $searchTerm = strtolower(trim($this->search));
            $items = collect($items)->filter(fn($item) => 
                str_contains(strtolower($item['name'] ?? ''), $searchTerm)
            )->all();
        }

        return $items;
    }

    public function updatedType()
    {
        $this->category = '';
        $this->subcategory = '';
    }

    public function render()
    {
        return view('livewire.crafting-item-list', [
            'typeOptions' => $this->typeOptions,
            'categoryOptions' => $this->categoryOptions,
            'subcategoryOptions' => $this->subcategoryOptions,
            'items' => $this->filteredItems,
        ]);
    }
}