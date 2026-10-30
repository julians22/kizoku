@extends('layouts.app')

@section('title', 'Terms & Privacy - Kizoku')

@section('content')
<section class="px-6 pt-32 pb-20">
    <div class="mx-auto max-w-4xl">
        @if ($termsContent)
            <div class="text-gray-700 text-sm lg:text-base leading-relaxed [&_h1]:mb-12 [&_h1]:font-saa-series-e-dot [&_h1]:text-3xl [&_h1]:2xl:text-4xl [&_h1]:uppercase [&_h1]:tracking-widest [&_h2]:mt-10 [&_h2]:mb-3 [&_h2]:font-medium [&_h2]:text-lg [&_h2]:uppercase [&_h2]:tracking-widest [&_ul]:space-y-1 [&_ul]:list-disc [&_ul]:list-inside [&_p+p]:mt-3 [&_ul+p]:mt-3 [&_p+ul]:mt-3 [&_strong]:font-semibold">
                @markdown($termsContent)
            </div>
        @endif
    </div>
</section>
@endsection
