<div>
    <!-- Filter -->
    <div class="flex flex-col gap-4 mb-6 md:flex-row">
        {{-- Select Type --}}
        <select wire:model.live="type" class="w-full select select-bordered md:w-40">
            <option value="">All Types</option>
            @foreach ($typeOptions as $typeOption)
                <option value="{{ $typeOption }}">{{ ucfirst($typeOption) }}</option>
            @endforeach
        </select>

        {{-- Select Category --}}
        <select wire:model.live="category" class="w-full select select-bordered md:w-40"
            {{ empty($categoryOptions) ? 'disabled' : '' }}>
            <option value="">All Categories</option>
            @foreach ($categoryOptions as $cat)
                <option value="{{ $cat['id'] }}">{{ $cat['name'] }}</option>
            @endforeach
        </select>

        {{-- Select Subcategory --}}
        <select wire:model.live="subcategory" class="w-full select select-bordered md:w-40"
            {{ empty($subcategoryOptions) ? 'disabled' : '' }}>
            <option value="">All Subcategories</option>
            @foreach ($subcategoryOptions as $sub)
                <option value="{{ $sub['id'] }}">{{ $sub['name'] }}</option>
            @endforeach
        </select>

        {{-- Select Tier --}}
        <select wire:model.live="tier" class="w-full select select-bordered md:w-40">
            <option value="">All Tiers</option>
            @for ($i = 1; $i <= 8; $i++)
                <option value="{{ $i }}.0">T{{ $i }}</option>
            @endfor
        </select>

        {{-- Search --}}
        <div class="relative w-full md:w-40">
            <input type="text" placeholder="Cari item..." wire:model.live="search"
                class="w-full pr-10 input input-bordered" />
            <span class="absolute text-gray-400 transform -translate-y-1/2 right-3 top-1/2">🔍</span>
        </div>
    </div>

    <!-- Daftar Item -->
    @if (!empty($items))
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-4">
            @foreach ($items as $item)
                <a href="{{ route('crafting-calculator', ['type' => $item['category']['type'], 'id' => $item['id']]) }}"
                    class="block">
                    <div
                        class="flex items-center gap-3 p-3 transition-shadow rounded-lg shadow bg-base-100 hover:shadow-md">
                        <div class="flex-shrink-0 w-12 h-12">
                            <img src="{{ $item['icon'] }}" alt="{{ $item['name'] }}"
                                class="object-contain w-full h-full bg-gray-100 rounded"
                                onerror="this.src='https://via.placeholder.com/50?text=NA'" />
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="text-sm font-medium truncate">{{ $item['name'] }}</h3>
                            <div class="mt-0.5 flex items-center gap-2 text-xs text-gray-500">
                                <span
                                    class="badge badge-xs badge-neutral">T{{ number_format((float) $item['tier'], 0, '.', '') }}</span>
                                <span class="truncate">{{ $item['subcategory']['name'] ?? '—' }}</span>
                            </div>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    @else
        <div class="py-10 text-center text-gray-500">
            Tidak ada item ditemukan.
        </div>
    @endif
</div>
