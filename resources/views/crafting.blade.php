@extends('layouts.app')

@section('content')
    <div class="mx-auto max-w-6xl">
        <div class="mb-6 flex items-center">
            <a href="{{ route('home') }}" class="btn btn-ghost btn-md">
                ← Back to Home
            </a>
            <h1 class="mx-auto text-3xl font-bold">🛠️ Crafting Profit Calculator Albion Online</h1>
        </div>

        @livewire('profit-calculator')
    </div>
@endsection
