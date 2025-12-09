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
        <select class="select select-bordered w-full md:w-auto">
            <option disabled selected>Tier</option>
            @for ($i = 4; $i <= 8; $i++)
                <option>T{{ $i }}</option>
            @endfor
        </select>

        {{-- Select Quality --}}
        <select class="select select-bordered w-full md:w-auto">
            <option disabled selected>Quality</option>
            <option>Normal</option>
            <option>Good</option>
            <option>Outstanding</option>
            <option>Excellent</option>
            <option>Masterpiece</option>
        </select>

        {{-- Select Enhancement --}}
        <select class="select select-bordered w-full md:w-auto">
            <option disabled selected>Enhancement</option>
            <option>0</option>
            <option>1</option>
            <option>2</option>
            <option>3</option>
            <option>4</option>
        </select>

        <input type="text" placeholder="Cari item..." wire:model.live="search" class="input input-bordered w-full" />
    </div>

    <!-- Detail Item -->
    <h1>Item akan muncul di sini</h1>
</div>
