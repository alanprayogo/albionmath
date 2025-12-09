@extends('layouts.app')

@section('content')
    <div class="mb-10 text-center">
        <h1 class="text-4xl font-bold text-primary">Albion Math</h1>
        <p class="max-w-2xl mx-auto mt-2 text-lg text-gray-600">
            Alat perhitungan profit crafting & refining untuk Albion Online.
            Gunakan data pasar real-time untuk memaksimalkan keuntungan Anda!
        </p>
    </div>

    <div class="grid max-w-4xl grid-cols-1 gap-6 mx-auto md:grid-cols-2">
        <!-- Card 1: Databook -->
        <a href="{{ route('databook') }}" class="block">
            <div class="transition-shadow shadow-xl card bg-base-100 hover:shadow-2xl">
                <div class="card-body">
                    <h2 class="text-2xl card-title">📚 Databook Item</h2>
                    <p>Lihat semua item Albion Online dengan harga pasar terkini.</p>
                </div>
            </div>
        </a>

        <!-- Card 2: Crafting Calculator -->
        <a href="{{ route('crafting') }}" class="block">
            <div class="transition-shadow shadow-xl card bg-base-100 hover:shadow-2xl">
                <div class="card-body">
                    <h2 class="text-2xl card-title">🧮 Crafting Calculator</h2>
                    <p>Hitung profit crafting secara real-time.</p>
                </div>
            </div>
        </a>

        <!-- Card 3: Refining Calculator -->
        <a href="{{ route('refining') }}" class="block">
            <div class="transition-shadow shadow-xl card bg-base-100 hover:shadow-2xl">
                <div class="card-body">
                    <h2 class="text-2xl card-title">🧮 Refining Calculator</h2>
                    <p>Hitung profit refining secara real-time.</p>
                </div>
            </div>
        </a>
    </div>
@endsection
