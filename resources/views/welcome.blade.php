@extends('layouts.app')

@section('content')
    <div class="mb-10 text-center">
        <h1 class="text-4xl font-bold text-primary">Albion Math</h1>
        <p class="max-w-2xl mx-auto mt-2 text-lg ">
            Maximize your Albion Online exploration experience with four powerful modules:
            <strong>Databook Item</strong> for exploring detailed item information,
            <strong>Crafting Calculator</strong> for accurately calculating crafting profits,
            <strong>Refining Calculator</strong> for finding the most efficient refining paths,
            and <strong>Meta Item</strong> to help you understand market trends and choose the most profitable items!
        </p>


    </div>

    <div class="grid max-w-4xl grid-cols-1 gap-6 mx-auto md:grid-cols-2">
        <!-- Card 1: Databook -->
        <a href="{{ route('databook') }}" class="block">
            <div class="transition-shadow shadow-xl card bg-base-100 hover:shadow-2xl">
                <div class="card-body">
                    <h2 class="text-2xl card-title">📚 Databook Item</h2>
                    <p>View detailed information for all Albion Online items.</p>
                </div>
            </div>
        </a>

        <!-- Card 2: Crafting Calculator -->
        <a href="{{ route('crafting') }}" class="block">
            <div class="transition-shadow shadow-xl card bg-base-100 hover:shadow-2xl">
                <div class="card-body">
                    <h2 class="text-2xl card-title">🛠️ Crafting Calculator</h2>
                    <p>Calculate crafting profits in real time.</p>
                </div>
            </div>
        </a>

        <!-- Card 3: Refining Calculator -->
        <a href="{{ route('refining') }}" class="block">
            <div class="transition-shadow shadow-xl card bg-base-100 hover:shadow-2xl">
                <div class="card-body">
                    <h2 class="text-2xl card-title">🔥 Refining Calculator</h2>
                    <p>Calculate refining profits in real time.</p>
                </div>
            </div>
        </a>
        
        <!-- Card 4: Meta Item -->
        <a href="{{ route('meta') }}" class="block">
            <div class="transition-shadow shadow-xl card bg-base-100 hover:shadow-2xl">
                <div class="card-body">
                    <h2 class="text-2xl card-title">📈 Meta Item</h2>
                    <p>Understand market trends and choose profitable items.</p>
                </div>
            </div>
        </a>
    </div>
@endsection
