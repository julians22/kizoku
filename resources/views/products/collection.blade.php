@extends('layouts.app')

@section('title', 'Kizoku | Parfume Collection')

@section('content')


<!-- Hero -->
<section
    class="flex justify-center items-center bg-gray-100 bg-cover bg-no-repeat bg-center aspect-[101/71]"
    style="background-image: url({{ asset('img/collection-hero-parfume.png') }});"
>
</section>

<!-- Collection -->
<section class="py-12">
    <div class="mx-auto px-4 max-w-6xl">

        <livewire:collection-component :category="$slug" />

    </div>
</section>


@endsection
