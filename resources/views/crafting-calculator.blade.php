@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="flex items-center mb-6">
        <a href="{{ route('crafting') }}" class="btn btn-ghost btn-md">
            ← Back to Crafting
        </a>
        <h1 class="mx-auto text-2xl font-bold">Crafting Calculator</h1>
    </div>

    @livewire('crafting-calculator', ['type' => $type, 'itemId' => $id])
</div>
@endsection