@extends('layouts.app')

@section('title', 'Terms & Privacy - Kizoku')

@section('content')
<section class="px-6 pt-32 pb-20">
    <div class="mx-auto max-w-4xl">
        @if ($termsContent)
            <div class="[&_ul]:space-y-1 [&_h2]:mt-10 [&_p+p]:mt-3 [&_p+ul]:mt-3 [&_ul+p]:mt-3 [&_h1]:mb-12 [&_h2]:mb-3 [&_h2]:font-medium [&_strong]:font-semibold text-gray-700 text-sm lg:text-base [&_h2]:text-lg [&_h2]:uppercase leading-relaxed [&_h2]:tracking-widest [&_ul]:list-disc [&_ul]:list-inside [&_h1]:title__page_title">
                @markdown($termsContent)
            </div>
        @endif
    </div>
</section>
@endsection
