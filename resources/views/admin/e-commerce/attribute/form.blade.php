@extends('layouts.admin.app')

@section('title')
    @isset($attribute)
        Edit attribute
    @else
        Add attribute
    @endisset
@endsection

@section('content')

    <section class="mb-6">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">
                    @isset($attribute)
                        Edit Attribute
                    @else
                        Add Attribute
                    @endisset
                </h1>
                <p class="mt-1 text-sm text-slate-500">
                    @isset($attribute)
                        Update the details of this attribute
                    @else
                        Create a new attribute for a category
                    @endisset
                </p>
            </div>
            <div class="flex items-center gap-3">
                <x-ui.button variant="outline" :href="routeHelper('attribute/list')">
                    <i class="fas fa-long-arrow-alt-left text-xs"></i> Back to List
                </x-ui.button>
                <ol class="flex items-center gap-1 text-sm text-slate-400">
                    <li><a href="{{ routeHelper('dashboard') }}" class="transition-colors hover:text-primary">Home</a></li>
                    <li><i class="fas fa-chevron-right text-[9px]"></i></li>
                    <li><a href="{{ routeHelper('attribute/list') }}" class="transition-colors hover:text-primary">Attributes</a></li>
                    <li><i class="fas fa-chevron-right text-[9px]"></i></li>
                    <li class="font-medium text-slate-600">
                        @isset($attribute)
                            Edit
                        @else
                            Add
                        @endisset
                    </li>
                </ol>
            </div>
        </div>
    </section>

    <section class="mb-6">
        <div class="mx-auto w-full max-w-3xl">
            <form
                action="{{ isset($attribute) ? routeHelper('attribute/' . $attribute->id) : route('admin.attribute.store') }}"
                method="POST">
                @csrf
                @isset($attribute)
                    @method('PUT')
                @endisset

                <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
                    <div class="flex items-center gap-3 border-b border-slate-200 px-4 py-4">
                        <div class="grid h-9 w-9 place-items-center rounded-lg bg-primary/10 text-primary">
                            <i class="fas fa-tags"></i>
                        </div>
                        <div>
                            <h3 class="text-base font-semibold text-slate-900">Attribute details</h3>
                            <p class="text-xs text-slate-500">Category and attribute name</p>
                        </div>
                    </div>

                    <div class="space-y-4 p-5">
                        <div>
                            <label for="category_id" class="mb-1 block text-sm font-medium text-slate-700">Category name</label>
                            <select name="category_id" id="category_id"
                                class="category block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm shadow-sm transition-shadow focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20"
                                required>
                                <option value="">Select Category</option>
                                @foreach ($categories as $category)
                                    <option
                                        @isset($attribute){{ $attribute->category_id == $category->id ? 'selected' : '' }}@endisset
                                        value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <x-ui.input
                            name="name"
                            label="Attribute Name"
                            type="text"
                            :value="$attribute->name ?? old('name')"
                            placeholder="Write attribute name"
                            required
                            autocomplete="off"
                        />
                    </div>

                    <div class="flex items-center justify-end gap-2 border-t border-slate-200 px-4 py-4">
                        <x-ui.button variant="outline" :href="routeHelper('attribute/list')">
                            Cancel
                        </x-ui.button>
                        <x-ui.button variant="primary" type="submit">
                            @isset($attribute)
                                <i class="fas fa-arrow-circle-up"></i>
                                Update
                            @else
                                <i class="fas fa-plus-circle"></i>
                                Submit
                            @endisset
                        </x-ui.button>
                    </div>
                </div>
            </form>
        </div>
    </section>
@endsection
