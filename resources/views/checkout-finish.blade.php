@extends('layouts.app')

@section('title', __('Status Pembayaran') . ' - Kizoku')

@section('content')
    <section class="px-6 pt-32 pb-20">
        <livewire:payment-finish-component />
    </section>
@endsection
