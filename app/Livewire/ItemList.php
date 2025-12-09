<?php

namespace App\Livewire;

use App\Services\AlbionApiService;
use Livewire\Component;

class ItemList extends Component
{
    public $search = '';
    public $type = '';
    public $category = '';      // ini sekarang berisi ID kategori (integer)
    public $subcategory = '';   // nanti berisi ID subkategori
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
        if (empty($this->type)) {
            return [];
        }

        $service = new AlbionApiService();
        return $service->getMainCategoriesByType($this->type);
    }

    // Ambil subkategori berdasarkan kategori yang dipilih
    public function getSubcategoryOptionsProperty()
    {
        if (empty($this->category) || !is_numeric($this->category)) {
            return [];
        }

        $service = new AlbionApiService();
        return $service->getSubcategoriesByCategoryId((int) $this->category);
    }

    public function render()
    {
        return view('livewire.item-list', [
            'typeOptions' => $this->typeOptions,
            'categoryOptions' => $this->categoryOptions,
            'subcategoryOptions' => $this->subcategoryOptions,
        ]);
    }
}