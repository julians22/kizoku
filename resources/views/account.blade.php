@extends('layouts.app')

@section('title', 'My Account - Kizoku')

@section('content')
    <section class="px-6 pt-32 pb-20">
        <div class="mx-auto max-w-6xl">
            <livewire:auth-component />
        </div>
    </section>
@endsection
