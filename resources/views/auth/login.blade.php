@extends('layouts.app')

@section('title', 'Login - Kizoku')

@section('content')
    <section class="px-6 pt-32 pb-20">
        <div class="mx-auto max-w-md">
            <h1 class="mb-2 font-light text-3xl tracking-wide">Customer Account</h1>
            <p class="mb-8 text-gray-500 text-sm">Sign in or create a Kizoku customer account to access your orders.</p>
            <livewire:auth-component :initial-mode="$authMode" :redirect-after-auth="true" />
        </div>
    </section>
@endsection
