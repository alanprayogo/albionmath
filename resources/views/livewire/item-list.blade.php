<div>
    <!-- Filter -->
    <div class="mb-6 flex flex-col gap-4 md:flex-row">
        {{-- Select Type --}}
        <select wire:model.live="type" class="select select-bordered w-full md:w-auto">
            <option value="">All Types</option>
            @foreach ($typeOptions as $typeOption)
                <option value="{{ $typeOption }}">{{ ucfirst($typeOption) }}</option>
            @endforeach
        </select>

        {{-- Select Category --}}
        <select wire:model.live="category" class="select select-bordered w-full md:w-auto"
            {{ empty($categoryOptions) ? 'disabled' : '' }}>
            <option value="">All Categories</option>
            @foreach ($categoryOptions as $cat)
                <option value="{{ $cat['id'] }}">{{ $cat['name'] }}</option>
            @endforeach
        </select>

        {{-- Select Subcategory --}}
        <select wire:model.live="subcategory" class="select select-bordered w-full md:w-auto"
            {{ empty($subcategoryOptions) ? 'disabled' : '' }}>
            <option value="">All Subcategories</option>
            @foreach ($subcategoryOptions as $sub)
                <option value="{{ $sub['id'] }}">{{ $sub['name'] }}</option>
            @endforeach
        </select>

        {{-- Select Tier --}}
        <select wire:model.live="tier" class="select select-bordered w-full md:w-auto">
            <option value="">All Tiers</option>
            @for ($i = 1; $i <= 8; $i++)
                <option value="{{ $i }}.0">T{{ $i }}</option>
            @endfor
        </select>

        <input type="text" placeholder="Cari item..." wire:model.live="search" class="input input-bordered w-full" />
    </div>

    <!-- Daftar Item -->
    @if (!empty($items))
        <div class="mt-6 grid grid-cols-1 gap-4 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-4">
            @foreach ($items as $item)
                <a href="{{ route('item.detail', ['type' => $item['category']['type'], 'id' => $item['id']]) }}"
                    class="block">
                    <div
                        class="bg-base-100 flex items-center gap-3 rounded-lg p-3 shadow transition-shadow hover:shadow-md">
                        <div class="h-12 w-12 flex-shrink-0">
                            <img src="{{ $item['icon'] }}" alt="{{ $item['name'] }}"
                                class="h-full w-full rounded bg-gray-100 object-contain"
                                onerror="this.src='https://via.placeholder.com/50?text=NA'" />
                        </div>
                        <div class="min-w-0 flex-1">
                            <h3 class="truncate text-sm font-medium">{{ $item['name'] }}</h3>
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
            @if ($type !== 'weapon' && !empty($type))
                Hanya tipe "weapon" yang didukung sementara.
            @else
                Tidak ada item ditemukan.
            @endif
        </div>
    @endif
</div>
