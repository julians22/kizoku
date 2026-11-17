@extends('layouts.app')

@section('title', 'FAQ - Kizoku')

@section('content')
<section class="px-6 pt-32 pb-20">
    <div class="mx-auto max-w-4xl">
        <h1 class="mb-12 title__page_title">@lang('FAQ')</h1>

        @foreach ($faqSections as $section)
            <div class="mb-12">
                <h2 class="title__xl">{{ $section['title'] }}</h2>
                <div class="border-navy-500 border-b divide-y divide-navy-500" x-data="{ open: null }">
                    @foreach ($section['items'] as $index => $item)
                        <div class="py-4">
                            <button type="button" class="flex justify-between items-center w-full text-left" @click="open = open === {{ $index }} ? null : {{ $index }}">
                                <span class="font-medium">{{ $item['question'] }}</span>
                                <span class="ml-4 text-xl" x-text="open === {{ $index }} ? '−' : '+'"></span>
                            </button>
                            <div x-show="open === {{ $index }}" x-cloak x-transition class="[&_ol]:space-y-2 [&_p+ol]:mt-2 [&_p+p]:mt-3 [&_p+ul]:mt-2 pt-3 [&_strong]:font-semibold text-gray-600 text-sm lg:text-base leading-relaxed [&_ol]:list-decimal [&_ol]:list-inside [&_ul]:list-disc [&_ul]:list-inside">
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
