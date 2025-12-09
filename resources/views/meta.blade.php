@extends('layouts.app')

@section('content')
    <div class="max-w-6xl mx-auto">
        <div class="flex items-center mb-6">
            <a href="{{ route('home') }}" class="btn btn-ghost btn-md">
                ← Back to Home
            </a>
            <h1 class="mx-auto text-3xl font-bold">📈 Meta Item Albion Online</h1>
        </div>

        <p>Coming soon...</p>
        {{-- @livewire('item-list') --}}
    </div>
@endsection
