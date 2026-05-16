@extends('layouts.frontend.app')

@push('meta')
    <meta name='description' content="Cart Products"/>
    <meta name='keywords' content="@foreach(\App\Models\Tag::all() as $tag){{$tag->name.', '}}@endforeach" />
@endpush

@section('title', 'Compare Products')

@section('content')

    <div class="checkout-right">
        <div class="container" style="width: 100%; margin: 20px auto; border: 1px solid #ddd; padding: 20px; box-sizing: border-box;">
            <table style="width: 100%; border-collapse: collapse; text-align: center;">
                <a href="{{ route('compare.clear') }}" class="btn btn-danger mb-2 float-right">Clear compare</a>
                <!-- Table Header -->
                <thead>
                    <tr>
                        <th style="padding: 10px; border: 1px solid #ddd; background-color: #f7f7f7; font-weight: bold;">Product</th>
                        @foreach(session('compare', []) as $product)
                            <th style="padding: 10px; border: 1px solid #ddd; background-color: #f7f7f7; font-weight: bold;">{{ $product['title'] }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    <!-- Product Images -->
                    <tr>
                        <td style="padding: 10px; border: 1px solid #ddd; font-weight: bold;">Image</td>
                        @foreach(session('compare', []) as $product)
                            <td style="padding: 10px;border: 1px solid #ddd;width: 20%;height: auto;">
                                <img src="{{ asset('uploads/product/' . $product['image']) }}" alt="{{ $product['title'] }}" style="width: 100%; height: auto;">
                            </td>
                        @endforeach
                    </tr>
                    <!-- Prices -->
                    <tr>
                        <td style="padding: 10px; border: 1px solid #ddd; font-weight: bold;">Price</td>
                        @foreach(session('compare', []) as $product)
                            <td style="padding: 10px; border: 1px solid #ddd;">${{ number_format($product['discount_price'], 2) }}</td>
                        @endforeach
                    </tr>
                    <!-- Regular Price -->
                    <tr>
                        <td style="padding: 10px; border: 1px solid #ddd; font-weight: bold;">Regular Price</td>
                        @foreach(session('compare', []) as $product)
                            <td style="padding: 10px; border: 1px solid #ddd;">${{ number_format($product['regular_price'], 2) }}</td>
                        @endforeach
                    </tr>
                    <!-- Short Description -->
                    <tr>
                        <td style="padding: 10px; border: 1px solid #ddd; font-weight: bold;">Description</td>
                        @foreach(session('compare', []) as $product)
                            <td style="padding: 10px; border: 1px solid #ddd;">{!! $product['short_description'] !!}</td>
                        @endforeach
                    </tr>
                    <!-- SKU -->
                    <tr>
                        <td style="padding: 10px; border: 1px solid #ddd; font-weight: bold;">SKU</td>
                        @foreach(session('compare', []) as $product)
                            <td style="padding: 10px; border: 1px solid #ddd;">{{ $product['sku'] }}</td>
                        @endforeach
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

@endsection