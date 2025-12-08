<?php

namespace App\Livewire;

use Livewire\Component;

class ProfitCalculator extends Component
{
    public $selectedItem = '';
    public $quantity = 1;
    public $hidePrice = 1200;     // harga manual, bisa diinput user
    public $leatherPrice = 900;

    // Data dummy resep crafting
    public function getItemRecipes()
    {
        return [
            't4_leather_armor' => [
                'name' => 'T4 Leather Armor',
                'output_per_craft' => 1,
                'materials' => [
                    ['name' => 'T3 Hide', 'amount' => 8, 'price_var' => 'hidePrice'],
                    ['name' => 'T3 Leather', 'amount' => 4, 'price_var' => 'leatherPrice'],
                ],
                'base_sell_price' => 12500, // nanti dari API
            ],
        ];
    }

    public function getSelectedRecipeProperty()
    {
        if (!$this->selectedItem) return null;
        return $this->getItemRecipes()[$this->selectedItem] ?? null;
    }

    public function getProfitProperty()
    {
        if (!$this->selectedRecipe) return 0;

        $totalMaterialCost = 0;
        foreach ($this->selectedRecipe['materials'] as $mat) {
            $price = $this->{$mat['price_var']};
            $totalMaterialCost += $price * $mat['amount'];
        }

        $outputValue = $this->selectedRecipe['base_sell_price'] * $this->quantity;
        return $outputValue - $totalMaterialCost;
    }

    public function render()
    {
        return view('livewire.profit-calculator', [
            'recipes' => $this->getItemRecipes(),
            'recipe' => $this->selectedRecipe,
            'profit' => $this->profit,
        ]);
    }
}
