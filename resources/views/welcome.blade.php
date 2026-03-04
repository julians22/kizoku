@extends('layouts.app')

@section('content')


    <!-- Section 1 -->
    <!-- Height: 100vh -->
    <section
        class="flex justify-center items-center bg-gray-100 bg-cover bg-no-repeat bg-center aspect-[45/32]"
        style="background-image: url({{ asset('img/S-1.png') }});"
    >
    </section>

    <!-- Section 2 -->
    <!-- Height: 100vh -->
    <section
        class="flex justify-center items-center bg-gray-100 bg-cover bg-no-repeat bg-center aspect-[45/32]"
        style="background-image: url({{ asset('img/S-2.png') }});"
    >
    </section>

    <!-- Section 3 -->
    <!-- Height: 100vh -->
    <section
        class="flex justify-center items-center bg-gray-100 bg-cover bg-no-repeat bg-center aspect-[45/32]"
        style="background-image: url({{ asset('img/S-3.png') }});"
    >
    </section>

    <!-- Section 4 -->
    <!-- Height: 100vh -->
    <section
        class="flex justify-center items-center bg-gray-100 bg-cover bg-no-repeat bg-center aspect-[45/32]"
        style="background-image: url({{ asset('img/S-4.png') }});"
    >
    </section>

    <!-- Section 5 -->
    <!-- Height: 100vh -->
    <!-- White background -->
    <section
        class="flex justify-center items-center bg-cover bg-no-repeat bg-center"
        >

        <div class="gap-x-10 grid grid-cols-12 mx-auto py-10 container">

            <div class="col-span-5">
                <img src="{{ asset('img/side-1.png') }}" alt="" class="w-full h-[90vh] object-cover">
            </div>

            <div class="flex flex-col justify-center col-span-7">
                <div class="text-lg">
                    <p>KIZUKO ISN'T JUST <br> A SCENT.</p>
                    <br>
                    <p>IT'S YOUR SIGNATURE.</p>
                    <p>ONE THAT DOESN'T FADE WITH TRENDS.</p>
                    <p>ONE THAT STAYS LIKE</p>
                    <p>A MEMORY.</p>

                    <br>

                    <a href="{{ route('collections.category', ['slug'=>'parfume']) }}" class="mt-2 underline underline-offset-1">@lang("VIEW PRODUCTS")</a>
                </div>
            </div>


        </div>

    </section>


@endsection
