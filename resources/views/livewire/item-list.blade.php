<div>
    <!-- Filter -->
    <div class="mb-6 flex flex-col gap-4 md:flex-row">
        <input type="text" placeholder="Cari item..." wire:model.live="search" class="input input-bordered w-full" />
        <!-- Anda bisa tambahkan dropdown kategori nanti -->
    </div>

    <!-- Daftar Item -->
    <div class="overflow-x-auto">
        <table class="table-zebra table w-full">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Tier</th>
                    <th>Tipe</th>
                    <th>Harga (Silver)</th>
                </tr>
            </thead>
            <tbody>
                @forelse($items as $item)
                    <tr>
                        <td>{{ $item['name'] }}</td>
                        <td>T{{ $item['tier'] }}</td>
                        <td>{{ ucfirst($item['type']) }}</td>
                        <td>{{ number_format($item['price'], 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center text-gray-500">Tidak ada item ditemukan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
