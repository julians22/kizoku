@extends('layouts.app')

@section('title', 'Login - Kizoku')

@section('content')
    <section class="px-6 pt-32 pb-20">
        <div class="mx-auto max-w-md">
            <h1 class="mb-2 title__3xl">@lang('Customer Account')</h1>
            <p class="mb-8 text-gray-500 text-sm">@lang('A more personal fragrance experience awaits. Sign in or create an account to view your orders, track deliveries, and manage your account.')</p>
            <livewire:auth-component :initial-mode="$authMode" :redirect-after-auth="true" />
        </div>
    </section>
@endsection
