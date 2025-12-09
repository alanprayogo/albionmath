@extends('layouts.app')

@section('content')
    <div class="mx-auto max-w-4xl">
        <!-- Flex container: tombol kiri, judul tengah -->
        <div class="mb-6 flex items-center">
            <a href="{{ route('databook') }}" class="btn btn-ghost btn-md">
                ← Back to Databook
            </a>
            <h1 class="mx-auto text-2xl font-bold">Detail Item</h1>
        </div>

        @livewire('item-detail', ['type' => $type, 'itemId' => $id])
    </div>
@endsection
