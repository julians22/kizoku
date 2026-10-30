@extends('layouts.app')

@section('title', 'FAQ - Kizoku')

@section('content')
<section class="px-6 pt-32 pb-20">
    <div class="mx-auto max-w-4xl">
        <h1 class="mb-12 font-saa-series-e-dot text-3xl 2xl:text-4xl uppercase tracking-widest">FAQ</h1>

        @foreach ($faqSections as $section)
            <div class="mb-12">
                <h2 class="mb-4 text-xl uppercase tracking-widest">{{ $section['title'] }}</h2>
                <div class="border-gray-200 border-t border-b divide-y divide-gray-200" x-data="{ open: null }">
                    @foreach ($section['items'] as $index => $item)
                        <div class="py-4">
                            <button type="button" class="flex justify-between items-center w-full text-left" @click="open = open === {{ $index }} ? null : {{ $index }}">
                                <span class="font-medium">{{ $item['question'] }}</span>
                                <span class="ml-4 text-xl" x-text="open === {{ $index }} ? '−' : '+'"></span>
                            </button>
                            <div x-show="open === {{ $index }}" x-cloak x-transition class="pt-3 text-gray-600 text-sm lg:text-base leading-relaxed [&_ol]:list-decimal [&_ol]:list-inside [&_ol]:space-y-2 [&_ul]:list-disc [&_ul]:list-inside [&_p+ol]:mt-2 [&_p+ul]:mt-2 [&_p+p]:mt-3 [&_strong]:font-semibold">
                                @markdown($item['answer'])
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
</section>
@endsection
