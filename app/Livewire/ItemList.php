<?php

namespace App\Livewire;

use Livewire\Component;

class ItemList extends Component
{
    public $search = '';
    public $category = 'all';

    // Getter untuk data item (dengan filter)
    public function getItemsProperty()
    {
        $items = [
            ['id' => 1, 'name' => 'T4 Leather Armor', 'tier' => 4, 'type' => 'armor', 'price' => 12500],
            ['id' => 2, 'name' => 'T5 Steel Sword', 'tier' => 5, 'type' => 'weapon', 'price' => 28000],
            ['id' => 3, 'name' => 'T3 Hide', 'tier' => 3, 'type' => 'material', 'price' => 1200],
            ['id' => 4, 'name' => 'T6 Royal Armor', 'tier' => 6, 'type' => 'armor', 'price' => 85000],
        ];

        return collect($items)
            ->filter(function ($item) {
                return str_contains(strtolower($item['name']), strtolower($this->search));
            })
            ->values();
    }

    public function render()
    {
        return view('livewire.item-list', [
            'items' => $this->items
        ]);
    }
}