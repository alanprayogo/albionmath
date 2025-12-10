<?php

namespace App\Livewire;

use App\Services\AlbionApiService;
use App\Services\CraftingService;
use Livewire\Component;

class CraftingCalculator extends Component
{
    public $type;
    public $itemId;
    public $itemData;
    public $recipe;
    public $selectedEnchant = '0';
    public $quantity = 1;
    public $sellPrice = 0;
    public $materialPrices = [];
    public $resourceReturnRate = '';
    public $usageFeePer100 = '';
    public $nutritionPerCraft = 100;

    public function mount($type, $itemId)
    {
        $this->type = $type;
        $this->itemId = $itemId;

        // Ambil data dasar item dari OpenAlbion
        $apiService = new AlbionApiService();
        $this->itemData = $apiService->getBaseItem($type, $itemId);

        // Ambil resep crafting
        $craftingService = new CraftingService();
        $this->recipe = $craftingService->getRecipe($type, $itemId);

        if ($this->recipe) {
            // Dapatkan daftar enchant yang valid
            $available = $this->getAvailableEnchantsProperty();
            if (!in_array($this->selectedEnchant, $available)) {
                $this->selectedEnchant = $available[0] ?? '0';
            }
            $this->loadVariantMaterials();
        }
    }

    public function getAvailableEnchantsProperty()
    {
        if (!$this->recipe) {
            return ['0'];
        }

        $variants = $this->recipe['variants'] ?? [];
        if (!is_array($variants) || empty($variants)) {
            return ['0'];
        }

        return array_keys($variants);
    }

    public function updatedSelectedEnchant()
    {
        if ($this->recipe) {
            $available = $this->getAvailableEnchantsProperty();
            if (!in_array($this->selectedEnchant, $available)) {
                $this->selectedEnchant = $available[0] ?? '0';
            }
        }
        $this->loadVariantMaterials();
    }

    private function loadVariantMaterials()
    {
        $this->materialPrices = [];

        if (!$this->recipe) return;

        $craftingService = new CraftingService();
        $variant = $craftingService->getVariant($this->recipe, $this->selectedEnchant);

        if ($variant && isset($variant['materials']) && is_array($variant['materials'])) {
            foreach ($variant['materials'] as $mat) {
                if (isset($mat['unique_name'])) {
                    $this->materialPrices[$mat['unique_name']] = 0;
                }
            }
        }
    }

    public function getTotalMaterialCostProperty()
    {
        if (!$this->recipe) return 0;

        $craftingService = new CraftingService();
        $variant = $craftingService->getVariant($this->recipe, $this->selectedEnchant);

        if (!$variant || !isset($variant['materials'])) return 0;

        $total = 0;
        foreach ($variant['materials'] as $mat) {
            if (!isset($mat['unique_name'], $mat['amount'])) continue;
            $price = $this->materialPrices[$mat['unique_name']] ?? 0;
            $total += $price * $mat['amount'] * $this->quantity;
        }
        return $total;
    }

    public function getEffectiveMaterialCostProperty()
    {
        if (!$this->recipe) return 0;

        $craftingService = new CraftingService();
        $variant = $craftingService->getVariant($this->recipe, $this->selectedEnchant);

        if (!$variant || !isset($variant['materials'])) return 0;

        $total = 0;
        $rrr = is_numeric($this->resourceReturnRate) ? (float) $this->resourceReturnRate / 100 : 0;

        foreach ($variant['materials'] as $mat) {
            if (!isset($mat['unique_name'], $mat['amount'])) continue;
            $price = $this->materialPrices[$mat['unique_name']] ?? 0;
            $effectiveAmount = $mat['amount'] * (1 - $rrr);
            $total += $price * $effectiveAmount * $this->quantity;
        }
        return $total;
    }

    public function getGoldCostProperty()
    {
        if (!$this->recipe) return 0;

        $craftingService = new CraftingService();
        $variant = $craftingService->getVariant($this->recipe, $this->selectedEnchant);

        if (!$variant || !isset($variant['cost_per_craft'])) return 0;

        return ($variant['cost_per_craft'] ?? 0) * $this->quantity;
    }

    public function getUsageFeeProperty()
    {
        $feePer100 = is_numeric($this->usageFeePer100) ? (float) $this->usageFeePer100 : 0;
        $totalNutrition = $this->nutritionPerCraft * $this->quantity;
        return ($totalNutrition / 100) * $feePer100;
    }

    public function getTotalCostProperty()
    {
        return $this->effectiveMaterialCost + $this->goldCost + $this->usageFee;
    }

    public function getTotalOutputProperty()
    {
        if (!$this->recipe) return 0;

        $craftingService = new CraftingService();
        $variant = $craftingService->getVariant($this->recipe, $this->selectedEnchant);

        if (!$variant || !isset($variant['per_craft'])) return 0;

        return ($variant['per_craft'] ?? 1) * $this->quantity;
    }

    public function getGrossIncomeProperty()
    {
        return $this->sellPrice * $this->totalOutput;
    }

    public function getTaxProperty()
    {
        return $this->grossIncome * 0.04;
    }

    public function getSellOrderFeeProperty()
    {
        return $this->grossIncome * 0.025;
    }

    public function getNetIncomeProperty()
    {
        return $this->grossIncome - $this->tax - $this->sellOrderFee;
    }

    public function getProfitProperty()
    {
        return $this->netIncome - $this->totalCost;
    }

    public function render()
    {
        $craftingService = new CraftingService();
        $variant = null;
        $availableEnchants = ['0'];

        if ($this->recipe) {
            $availableEnchants = $this->getAvailableEnchantsProperty();
            $variant = $craftingService->getVariant($this->recipe, $this->selectedEnchant);
        }

        return view('livewire.crafting-calculator', [
            'variant' => $variant,
            'availableEnchants' => $availableEnchants,
            'totalMaterialCost' => $this->totalMaterialCost,
            'effectiveMaterialCost' => $this->effectiveMaterialCost,
            'goldCost' => $this->goldCost,
            'usageFee' => $this->usageFee,
            'totalCost' => $this->totalCost,
            'totalOutput' => $this->totalOutput,
            'grossIncome' => $this->grossIncome,
            'tax' => $this->tax,
            'sellOrderFee' => $this->sellOrderFee,
            'netIncome' => $this->netIncome,
            'profit' => $this->profit,
        ]);
    }
}