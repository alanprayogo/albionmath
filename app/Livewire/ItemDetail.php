<?php

namespace App\Livewire;

use App\Services\AlbionApiService;
use Livewire\Component;

class ItemDetail extends Component
{
    public $type;
    public $itemId;
    public $selectedTier = '';
    public $selectedQuality = '';
    public $selectedEnchant = '';

    public function mount($type, $itemId)
    {
        $this->type = $type;
        $this->itemId = $itemId;
    }

    public function getStatsDataProperty()
    {
        $service = new AlbionApiService();
        return $service->getItemStats($this->type, $this->itemId);
    }

    public function getBaseItemProperty()
    {
        $service = new AlbionApiService();
        return $service->getBaseItem($this->type, $this->itemId);
    }

    public function getTierOptionsProperty()
    {
        if (!empty($this->statsData)) {
            return collect($this->statsData)
                ->pluck('item.tier')
                ->unique()
                ->sort()
                ->values()
                ->all();
        }
        // Fallback: ambil tier dari base item
        if ($this->baseItem && isset($this->baseItem['tier'])) {
            return [(string) $this->baseItem['tier']];
        }
        return [];
    }

    public function getQualityOptionsProperty()
    {
        if (!empty($this->statsData)) {
            return collect($this->statsData)
                ->pluck('quality')
                ->unique()
                ->values()
                ->all();
        }
        return [];
    }

    public function getEnchantOptionsProperty()
    {
        if (!empty($this->statsData)) {
            return collect($this->statsData)
                ->pluck('enchantment')
                ->unique()
                ->sort()
                ->values()
                ->all();
        }
        return [];
    }

    public function getFilteredStatProperty()
    {
        if (empty($this->statsData)) {
            return null;
        }

        $collection = collect($this->statsData);

        // Filter hanya jika nilai dipilih
        if ($this->selectedTier !== '') {
            $collection = $collection->where('item.tier', $this->selectedTier);
        }
        if ($this->selectedQuality !== '') {
            $collection = $collection->where('quality', $this->selectedQuality);
        }
        if ($this->selectedEnchant !== '') {
            $collection = $collection->where('enchantment', (int) $this->selectedEnchant);
        }

        // 🔥 Jika hasil filter kosong, kembalikan varian pertama
        if ($collection->isEmpty()) {
            return $this->statsData[0] ?? null;
        }

        return $collection->first();
    }

    public function render()
    {
        return view('livewire.item-detail', [
            'stat' => $this->filteredStat,
            'baseItem' => $this->baseItem,
            'hasDetailedStats' => !empty($this->statsData),
            'tierOptions' => $this->tierOptions,
            'qualityOptions' => $this->qualityOptions,
            'enchantOptions' => $this->enchantOptions,
        ]);
    }
}