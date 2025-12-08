@extends('layouts.app')

@section('content')
    <div class="mb-10 text-center">
        <h1 class="text-primary text-4xl font-bold">AlbionMath</h1>
        <p class="mx-auto mt-2 max-w-2xl text-lg text-gray-600">
            Alat perhitungan profit crafting & refining untuk Albion Online.
            Gunakan data pasar real-time untuk memaksimalkan keuntungan Anda!
        </p>
    </div>

    <div class="mx-auto grid max-w-4xl grid-cols-1 gap-6 md:grid-cols-2">
        <!-- Card 1: Database -->
        <a href="{{ route('database') }}" class="block">
            <div class="card bg-base-100 shadow-xl transition-shadow hover:shadow-2xl">
                <div class="card-body">
                    <h2 class="card-title text-2xl">📚 Database Item</h2>
                    <p>Lihat semua item Albion Online dengan harga pasar terkini.</p>
                </div>
            </div>
        </a>

        <!-- Card 2: Crafting Calculator -->
        <a href="{{ route('calculator') }}" class="block">
            <div class="card bg-base-100 shadow-xl transition-shadow hover:shadow-2xl">
                <div class="card-body">
                    <h2 class="card-title text-2xl">🧮 Crafting Calculator</h2>
                    <p>Hitung profit crafting & refining secara real-time.</p>
                </div>
            </div>
        </a>
    </div>
@endsection
