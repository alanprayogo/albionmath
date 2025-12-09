@extends('layouts.app')

@section('content')
    <div class="mx-auto max-w-4xl">
        <h1 class="mb-4 text-2xl font-bold">Detail Item</h1>

        @livewire('item-detail', ['type' => $type, 'itemId' => $id])
    </div>
@endsection
