<div>
    {{-- Statistik Item --}}
    @if ($hasDetailedStats && $stat)
        <div class="flex flex-col gap-6 md:flex-row">
            <div class="flex-shrink-0">
                <img src="{{ $stat['icon'] }}" alt="{{ $stat['item']['name'] ?? 'Item' }}"
                    class="object-contain w-32 h-32 bg-gray-100 rounded" />
            </div>
            <div class="flex-1">
                <h2 class="text-xl font-bold">{{ $stat['item']['name'] ?? '—' }}</h2>
                <p class="text-gray-100">
                    Tier: T{{ number_format((float) ($stat['item']['tier'] ?? 0), 0, '.', '') }} •
                    Quality: {{ $stat['quality'] ?? '—' }} •
                    Enchant: +{{ $stat['enchantment'] ?? '0' }}
                </p>

                <div class="flex flex-wrap gap-3 mt-4">

                    <select wire:model.live="selectedQuality" class="text-sm select select-bordered">
                        @foreach ($qualityOptions as $q)
                            <option value="{{ $q }}">{{ $q }}</option>
                        @endforeach
                    </select>

                    <select wire:model.live="selectedEnchant" class="text-sm select select-bordered">
                        @foreach ($enchantOptions as $e)
                            <option value="{{ $e }}">+{{ $e }}</option>
                        @endforeach
                    </select>
                </div>

                @if (!empty($stat['stats']))
                    <div class="mt-6">
                        <h3 class="mb-2 font-semibold">Statistik:</h3>
                        <div class="grid grid-cols-1 gap-2 sm:grid-cols-2">
                            @foreach ($stat['stats'] as $s)
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-100">{{ $s['name'] ?? '—' }}:</span>
                                    <span class="font-medium">{{ $s['value'] ?? '—' }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    @elseif($baseItem)
        <div class="flex flex-col gap-6 md:flex-row">
            <div class="flex-shrink-0">
                <img src="{{ $baseItem['icon'] }}" alt="{{ $baseItem['name'] }}"
                    class="object-contain w-32 h-32 bg-gray-100 rounded" />
            </div>
            <div class="flex-1">
                <h2 class="text-xl font-bold">{{ $baseItem['name'] }}</h2>
                <p class="text-gray-100">
                    Tier: T{{ number_format((float) ($baseItem['tier'] ?? 0), 0, '.', '') }} •
                    Category: {{ $baseItem['subcategory']['name'] ?? '—' }}
                </p>
                <div class="mt-4 text-gray-100">
                    <em>Data statistik detail tidak tersedia untuk item ini.</em>
                </div>
            </div>
        </div>
    @endif

    {{-- TAMPILKAN SPELL JIKA ADA --}}
    @php
        $validSpellTypes = ['weapon', 'armor', 'accessory'];
    @endphp

    @if (in_array($this->type, $validSpellTypes) && !empty($spellsData))
        <div class="mt-8">
            <h3 class="mb-4 text-lg font-bold">Spells</h3>

            {{-- Loop grup spell: First Slot, Second Slot, dll. --}}
            @foreach ($spellsData as $spellGroup)
                <div class="mb-4 border rounded-lg">
                    <div class="px-4 py-2 font-medium bg-base-200">
                        {{ $spellGroup['slot'] }} ({{ count($spellGroup['spells'] ?? []) }})
                    </div>
                    <div class="p-4 space-y-3">
                        {{-- Loop spell dalam grup --}}
                        @foreach ($spellGroup['spells'] as $spell)
                            @php
                                $isOpen = in_array($spell['id'], $this->openSpells);
                            @endphp

                            <div class="overflow-hidden border rounded-lg">
                                <div class="flex items-center gap-3 p-3 cursor-pointer bg-base-100 hover:bg-base-200"
                                    wire:click="toggleSpell({{ $spell['id'] }})">
                                    <img src="{{ $spell['icon'] }}" alt="{{ $spell['name'] }}"
                                        class="object-contain w-10 h-10 bg-gray-100 rounded"
                                        onerror="this.src='https://via.placeholder.com/40?text=S'" />
                                    <span class="font-medium">{{ $spell['name'] }}</span>
                                    <span class="ml-auto text-gray-100">
                                        {{ $isOpen ? '▲' : '▼' }}
                                    </span>
                                </div>

                                @if ($isOpen)
                                    <div class="p-3 border-t bg-base-200">
                                        <p class="mb-3 text-sm text-gray-100">{{ $spell['description'] ?? '—' }}</p>
                                        @if (!empty($spell['attributes']))
                                            <div class="grid grid-cols-2 text-xs gap-x-4 gap-y-2">
                                                @foreach ($spell['attributes'] as $attr)
                                                    <div class="flex justify-between">
                                                        <span class="text-gray-100">{{ $attr['name'] }}:</span>
                                                        <span class="font-medium">{{ $attr['value'] }}</span>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
