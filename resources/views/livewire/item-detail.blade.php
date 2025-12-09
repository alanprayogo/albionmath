<div>
    @if ($hasDetailedStats && $stat)
        <!-- Tampilan dengan statistik detail -->
        <div class="flex flex-col gap-6 md:flex-row">
            <div class="flex-shrink-0">
                <img src="{{ $stat['icon'] }}" alt="{{ $stat['item']['name'] ?? 'Item' }}"
                    class="h-32 w-32 rounded bg-gray-100 object-contain" />
            </div>
            <div class="flex-1">
                <h2 class="text-xl font-bold">{{ $stat['item']['name'] ?? '—' }}</h2>
                <p class="text-gray-600">
                    Tier: T{{ number_format((float) ($stat['item']['tier'] ?? 0), 0, '.', '') }} •
                    Quality: {{ $stat['quality'] ?? '—' }} •
                    Enchant: +{{ $stat['enchantment'] ?? '0' }}
                </p>

                <!-- Filter -->
                <div class="mt-4 flex flex-wrap gap-3">
                    @if (count($qualityOptions) > 0)
                        <select wire:model.live="selectedQuality" class="select select-bordered text-sm">
                            <option disabled="">Select Qualities</option>
                            @foreach ($qualityOptions as $q)
                                <option value="{{ $q }}">{{ $q }}</option>
                            @endforeach
                        </select>
                    @endif

                    @if (count($enchantOptions) > 0)
                        <select wire:model.live="selectedEnchant" class="select select-bordered text-sm">
                            <option disabled="">Select Enchants</option>
                            @foreach ($enchantOptions as $e)
                                <option value="{{ $e }}">+{{ $e }}</option>
                            @endforeach
                        </select>
                    @endif
                </div>

                @if (!empty($stat['stats']))
                    <div class="mt-6">
                        <h3 class="mb-2 font-semibold">Statistik:</h3>
                        <div class="grid grid-cols-1 gap-2 sm:grid-cols-2">
                            @foreach ($stat['stats'] as $s)
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">{{ $s['name'] ?? '—' }}:</span>
                                    <span class="font-medium">{{ $s['value'] ?? '—' }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    @elseif($baseItem)
        <!-- Fallback: tampilkan data dasar -->
        <div class="flex flex-col gap-6 md:flex-row">
            <div class="flex-shrink-0">
                <img src="{{ $baseItem['icon'] }}" alt="{{ $baseItem['name'] }}"
                    class="h-32 w-32 rounded bg-gray-100 object-contain" />
            </div>
            <div class="flex-1">
                <h2 class="text-xl font-bold">{{ $baseItem['name'] }}</h2>
                <p class="text-gray-600">
                    Tier: T{{ number_format((float) ($baseItem['tier'] ?? 0), 0, '.', '') }} •
                    Category: {{ $baseItem['subcategory']['name'] ?? '—' }}
                </p>
                <div class="mt-4 text-gray-500">
                    <em>Data statistik detail tidak tersedia untuk item ini.</em>
                </div>
            </div>
        </div>
    @else
        <div class="py-10 text-center text-gray-500">
            Item tidak ditemukan.
        </div>
    @endif
</div>
