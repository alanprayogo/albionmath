<div class="space-y-6">
    <!-- Pilih Item -->
    <div class="form-control">
        <label class="label"><span class="label-text">Pilih Item untuk Crafting</span></label>
        <select wire:model.live="selectedItem" class="select select-bordered">
            <option value="">-- Pilih Item --</option>
            @foreach ($recipes as $key => $recipe)
                <option value="{{ $key }}">{{ $recipe['name'] }}</option>
            @endforeach
        </select>
    </div>

    @if ($recipe)
        <!-- Jumlah Crafting -->
        <div class="form-control">
            <label class="label"><span class="label-text">Jumlah Crafting</span></label>
            <input type="number" wire:model.live="quantity" min="1" class="input input-bordered w-32" />
        </div>

        <!-- Input Harga Manual -->
        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
            @foreach ($recipe['materials'] as $mat)
                @php($priceVar = $mat['price_var'])
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">{{ $mat['name'] }} (x{{ $mat['amount'] }})</span>
                    </label>
                    <input type="number" wire:model.live="{{ $priceVar }}" class="input input-bordered"
                        placeholder="Harga per unit" />
                </div>
            @endforeach
        </div>

        <!-- Hasil Perhitungan -->
        <div class="bg-base-200 mt-6 rounded-lg p-4">
            <h3 class="text-lg font-bold">Hasil Perhitungan</h3>
            <p>Profit:
                <span class="{{ $profit >= 0 ? 'text-green-600' : 'text-red-600' }} font-bold">
                    {{ number_format($profit, 0, ',', '.') }} silver
                </span>
            </p>
        </div>
    @else
        <div class="py-8 text-center text-gray-500">Pilih item untuk mulai menghitung.</div>
    @endif
</div>
