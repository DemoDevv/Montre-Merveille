@extends('layouts.default')

@section('content')
    <div class="px-12 md:px-26 lg:px-44 mt-20 mb-12">
        <!-- Barre de recherche -->
        <form class="flex flex-col gap-1" action="{{ route('product.index') }}" method="GET">
            <div class="relative">
                <input
                    class="border-b-2 border-gray-400 focus:border-gray-500 py-2 text-2xl w-full md:text-4xl font-thin focus:outline-none pl-4 transition"
                    name="name" placeholder="Rechercher des montres" value="{{ $name }}" />

                <button type="submit" class="absolute inset-y-0 right-0">
                    <img class="mb-6" src="{{ asset('images/search-icon.svg') }}" />
                </button>
            </div>


            <!-- Filtres -->
            <div class="mt-32 border-b border-gray-400 flex gap-8 py-4">
                <div>
                    <span class="text-gray-400 font-thin">Filtrer par</span>
                </div>
                <div>
                    <span id="filter-size-title" class="cursor-pointer transition hover:text-gray-500">Taille</span>
                </div>
                <div>
                    <span id="filter-movement-title" class="cursor-pointer transition hover:text-gray-500">Mouvement</span>
                </div>
                <div>
                    <span id="filter-material-title" class="cursor-pointer transition hover:text-gray-500">Matériaux</span>
                </div>

                <div class="flex gap-4 ml-auto">
                    <a href="{{ route('product.index') }}">Retirer les filtres</a>

                    <button class="font-semibold underline underline-offset-4">Rechercher</button>
                </div>
            </div>

            <div class="flex flex-col lg:grid lg:grid-cols-3 gap-5">
                <div id="filter-size-container" class="mt-8 hidden flex gap-8">
                    <label class="font-medium">Taille maximale (mm.)</label>
                    <div class="flex gap-2">
                        <input class="border-b-2 border-gray-400 font-thin text-lg focus:outline-none" type="range"
                            name="size" value="{{ $size }}" min="0" max="60"
                            oninput="this.nextElementSibling.value = this.value" />
                        <output>{{ $size }}</output>
                    </div>
                </div>

                <div id="filter-movement-container" class="mt-8 hidden flex gap-8">
                    <label class="font-medium">Type de mouvement</label>
                    <div class="flex gap-2">
                        <select name="movement">
                            <option selected disabled>--- Mouvement ---</option>

                            @foreach (App\Models\Product::$movements as $optMovement)
                                <option value="{{ $optMovement }}" {{ $optMovement == $movement ? 'selected' : '' }}>
                                    {{ $optMovement }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div id="filter-material-container" class="mt-8 hidden flex gap-8">
                    <label class="font-medium">Matériaux</label>
                    <div class="flex gap-2">
                        <select name="material">
                            <option selected disabled>--- Matériaux ---</option>

                            @foreach (App\Models\Product::$materials as $optMaterial)
                                <option value="{{ $optMaterial }}" {{ $optMaterial == $material ? 'selected' : '' }}>
                                    {{ $optMaterial }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="flex items-center place-content-between">
                {{-- nb élément de la requête --}}
                <p class="my-8 text-gray-400 font-thin">{{ $products_count }} montres</p>

                {{-- pagination --}}
                <div class="flex items-center gap-1">
                    @for ($i = 1; $i <= $nb_pages; $i++)
                        @if ($i == $page)
                            {{-- index de la page actuel --}} <span class="text-lg font-bold">{{ $i }}</span>
                        @else
                            <a href="{{ route('product.index', ['page' => $i]) }}"
                                class="text-base font-thin">{{ $i }}</a>
                        @endif
                    @endfor
                </div>
            </div>

            <div class="grid grid-cols-3 gap-4">
                @foreach ($products as $product)
                    <article class="bg-neutral-200 p-6 flex flex-col items-center gap-6 group">
                        <a href="{{ route('product.show', ['product' => $product->id]) }}" class="text-lg font-bold"><img
                                class="w-40 drop-shadow-lg transition ease-in group-hover:scale-105 duration-500"
                                src="{{ asset('images/montre_1.png') }}" /></a>

                        <p class="flex flex-col items-center gap-2">
                            <a href="{{ route('product.show', ['product' => $product->id]) }}"
                                class="text-lg text-balance text-center font-bold">{{ $product->name }}</a>
                            <span class="font-thin">Ultra-Complication Universelle</span>
                        </p>

                        <span class="text-sm font-light text-gray-500">
                            {{ $product->movement }}, {{ $product->size }}mm, {{ $product->material }}
                        </span>
                    </article>
                @endforeach
            </div>
        </form>

        <script>
            const filtersName = ["size", "movement", "material"];

            filtersName.forEach(filter => {
                const filterTitle = document.getElementById(`filter-${filter}-title`);
                const filterContainer = document.getElementById(`filter-${filter}-container`);

                filterTitle.addEventListener('click', function(e) {
                    filterContainer.classList.toggle("hidden");
                });
            });
        </script>
    @endsection
