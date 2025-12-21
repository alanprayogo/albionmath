<div>
    @if (!$itemData)
        <div class="py-10 text-center text-gray-500">
            Data item tidak ditemukan.
        </div>
        @return
    @endif

    @if (!$recipe)
        <div class="py-10 text-center">
            <div class="flex flex-col items-center gap-4">
                <img src="{{ $itemData['icon'] ?? 'https://via.placeholder.com/64?text=Item' }}"
                    alt="{{ $itemData['name'] ?? 'Item' }}" class="object-contain w-16 h-16 rounded"
                    onerror="this.src='https://via.placeholder.com/64?text=Item'" />
                <div>
                    <h2 class="text-xl font-bold">{{ $itemData['name'] ?? 'Item' }}</h2>
                    <p class="mt-2 text-gray-500">Data crafting belum tersedia.</p>
                    <p class="text-sm text-gray-400">ID: {{ $type }}/{{ $itemId }}</p>
                </div>
            </div>
        </div>
        @return
    @endif

    <!-- Layout Utama -->
    <div class="grid grid-cols-1 gap-6 mb-6 md:grid-cols-2">
        <!-- Kiri: Item Info + Form -->
        <div>
            <!-- Header -->
            <div class="flex flex-col items-center gap-4 mb-6 sm:flex-row">
                <img src="{{ $itemData['icon'] ?? 'https://via.placeholder.com/64?text=Item' }}"
                    alt="{{ $recipe['name'] ?? ($itemData['name'] ?? 'Item') }}"
                    class="object-contain w-16 h-16 rounded"
                    onerror="this.src='https://via.placeholder.com/64?text=Item'" />
                <div class="text-center sm:text-left">
                    <h2 class="text-xl font-bold">{{ $recipe['name'] ?? ($itemData['name'] ?? 'Item') }}</h2>
                    <div class="text-sm text-gray-600">Enchantment: +{{ $selectedEnchant }}</div>
                    @if ($variant && isset($variant['per_craft']))
                        <div class="mt-2">
                            <span class="badge badge-info">
                                Output: {{ $variant['per_craft'] }} item{{ $variant['per_craft'] > 1 ? 's' : '' }} per
                                craft
                            </span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Enchantment -->
            @if (count($availableEnchants) > 1)
                <div class="mb-6">
                    <label class="mb-2 label">
                        <span class="label-text">Enchantment Level</span>
                    </label>
                    <select wire:model.live="selectedEnchant" class="w-32 select select-bordered">
                        @foreach ($availableEnchants as $enchant)
                            <option value="{{ $enchant }}">+{{ $enchant }}</option>
                        @endforeach
                    </select>
                </div>
            @endif

            <!-- Input Form -->
            <div class="grid grid-cols-1 gap-4 mb-6 md:grid-cols-2">
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Jumlah Crafting</span>
                    </label>
                    <input type="number" wire:model.live.number="quantity" min="1" class="input input-bordered"
                        placeholder="1" />
                </div>

                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Harga Jual per Item</span>
                    </label>
                    <input type="number" wire:model.live.number="sellPrice" class="input input-bordered"
                        placeholder="5000" />
                </div>

                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Resource Return Rate (%)</span>
                    </label>
                    <input type="number" wire:model.live.number="resourceReturnRate" min="0" max="100"
                        class="input input-bordered" placeholder="40" />
                </div>

                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Usage Fee / 100 Nutrition</span>
                    </label>
                    <input type="number" wire:model.live.number="usageFeePer100" class="input input-bordered"
                        placeholder="950" />
                </div>
            </div>

            <!-- Bahan Crafting -->
            @if ($variant && isset($variant['materials']) && is_array($variant['materials']) && count($variant['materials']) > 0)
                <h3 class="mb-3 font-semibold">Bahan Crafting (per craft)</h3>
                <div class="space-y-2">
                    @foreach ($variant['materials'] as $mat)
                        @if (isset($mat['name']) && isset($mat['amount']) && isset($mat['unique_name']))
                            <div class="flex items-center justify-between">
                                <span>{{ $mat['name'] }} (x{{ $mat['amount'] }})</span>
                                <input type="number" wire:model.live.number="materialPrices.{{ $mat['unique_name'] }}"
                                    class="w-32 text-right input input-bordered input-sm" placeholder="0" />
                            </div>
                        @endif
                    @endforeach
                </div>
            @else
                <div class="text-gray-500">
                    Data bahan crafting belum tersedia.
                </div>
            @endif
        </div>

        <!-- Kanan: Hasil Perhitungan -->
        <div class="p-4 rounded-lg bg-base-200">
            <h3 class="mb-3 text-lg font-bold">Hasil Perhitungan</h3>
            <div class="grid grid-cols-2 gap-2 mb-3 text-sm">
                <div>Biaya Material:</div>
                <div class="font-medium text-right">{{ number_format($effectiveMaterialCost, 0, ',', '.') }} silver
                </div>

                <div>Biaya Emas:</div>
                <div class="font-medium text-right">{{ number_format($goldCost, 0, ',', '.') }} silver</div>

                <div>Usage Fee:</div>
                <div class="font-medium text-right">{{ number_format($usageFee, 0, ',', '.') }} silver</div>

                <div class="font-semibold">Total Biaya:</div>
                <div class="font-semibold text-right">{{ number_format($totalCost, 0, ',', '.') }} silver</div>
            </div>

            <div class="pt-3 mt-3 border-t">
                <div class="grid grid-cols-2 gap-2 mb-2 text-sm">
                    <div>Total Item Dihasilkan:</div>
                    <div class="text-right">{{ number_format($totalOutput, 0, ',', '.') }}</div>

                    <div>Pendapatan Kotor:</div>
                    <div class="text-right">{{ number_format($grossIncome, 0, ',', '.') }} silver</div>

                    <div>Tax (4%):</div>
                    <div class="text-right text-red-600">-{{ number_format($tax, 0, ',', '.') }} silver</div>

                    <div>Sell Order Fee (2.5%):</div>
                    <div class="text-right text-red-600">-{{ number_format($sellOrderFee, 0, ',', '.') }} silver</div>

                    <div class="font-semibold">Pendapatan Bersih:</div>
                    <div class="font-semibold text-right">{{ number_format($netIncome, 0, ',', '.') }} silver</div>
                </div>

                <div class="flex justify-between pt-2 mt-4 border-t">
                    <span class="text-lg font-bold">Profit:</span>
                    <span class="{{ $profit >= 0 ? 'text-green-600' : 'text-red-600' }} text-lg font-bold">
                        {{ number_format($profit, 0, ',', '.') }} silver
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>
