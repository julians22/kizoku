@extends('layouts.app')

@section('title', 'Track My Order - Kizoku')

@section('content')
<section class="px-6 pt-32 pb-20">
    <div class="mx-auto max-w-4xl">
        @if ($trackContent)
            <div class="[&_ol]:space-y-1 [&_ul]:space-y-1 [&_h2]:mt-10 [&_ol+p]:mt-3 [&_p+ol]:mt-3 [&_p+p]:mt-3 [&_p+ul]:mt-3 [&_ul+p]:mt-3 [&_h1]:mb-12 [&_h2]:mb-3 [&_h2]:font-medium [&_strong]:font-semibold text-gray-700 text-sm lg:text-base [&_h2]:text-lg leading-relaxed [&_h2]:tracking-widest [&_ol]:list-decimal [&_ol]:list-inside [&_ul]:list-disc [&_ul]:list-inside [&_h1]:title__page_title">
                @markdown($trackContent)
            </div>
        @endif
    </div>
</section>
@endsection
