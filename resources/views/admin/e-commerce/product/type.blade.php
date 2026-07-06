@extends('layouts.admin.app')

@section('title', 'Type List')

@section('content')

    <section class="mb-6">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">Product Types</h1>
                <p class="mt-1 text-sm text-slate-500">Choose a product type to start adding a new product</p>
            </div>
            <ol class="flex items-center gap-1 text-sm text-slate-400">
                <li><a href="{{ routeHelper('dashboard') }}" class="transition-colors hover:text-primary">Home</a></li>
                <li><i class="fas fa-chevron-right text-[9px]"></i></li>
                <li class="font-medium text-slate-600">Product Types</li>
            </ol>
        </div>
    </section>

    <section class="mb-6">
        <div class="grid grid-cols-2 gap-4 lg:grid-cols-4">
            <x-ui.stat-tile
                variant="warning"
                value="Inhouse"
                label="Add new product"
                icon="fas fa-plus"
                :href="route('admin.product.inhouse.create')"
            />
        </div>
    </section>

@endsection
