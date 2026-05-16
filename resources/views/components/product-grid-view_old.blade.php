@props(['product', 'classes' => 'col-lg-3 col-md-3 col-sm-4 col-4'])
<div class="product {{ $classes }} pxc" style="width: 200px !important;">
    <?php
    $typeid = $product->slug;
    ?>
    <div class="product-wrapper" style="height: 366px;">
        <style>
            .product-wrapper {
                overflow: hidden;
            }

            .pxc.product:hover .details {
                transition: all 0.4s;
                padding-top: 0 !important;
            }
        </style>
        <div class="pin">
            <div class="thumbnail">
                <a href="{{ route('product.details', $product->slug) }}">
                    <img src="{{ asset('uploads/product/' . $product->image) }}" alt="{{ $product->title }}"
                        loading="lazy">
                </a>
            </div>
            <div class="details">
                <div class="rating1" style="font-size:12px;text-align: left;">
                    @php
                        $hw = App\Models\wishlist::where('product_id', $product->id)
                            ->where('user_id', auth()->id())
                            ->first();
                        if ($hw) {
                            $color = '#54c8ec';
                        } else {
                            $color = '#a2acb5';
                        }
                        if ($product->reviews->count() > 0) {
                            $average_rating = $product->reviews->sum('rating') / $product->reviews->count();
                        } else {
                            $average_rating = 0;
                        }
                    @endphp
                    <div>
                        @if ($average_rating == 0)
                            <i class="far fa-star"></i>
                            <i class="far fa-star"></i>
                            <i class="far fa-star"></i>
                            <i class="far fa-star"></i>
                            <i class="far fa-star"></i>
                        @elseif ($average_rating > 0 && $average_rating < 1.5)
                            <i class="fas fa-star"></i>
                            <i class="far fa-star"></i>
                            <i class="far fa-star"></i>
                            <i class="far fa-star"></i>
                            <i class="far fa-star"></i>
                        @elseif ($average_rating >= 1.5 && $average_rating < 2)
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star-half-alt"></i>
                            <i class="far fa-star"></i>
                            <i class="far fa-star"></i>
                            <i class="far fa-star"></i>
                        @elseif ($average_rating >= 2 && $average_rating < 2.5)
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="far fa-star"></i>
                            <i class="far fa-star"></i>
                            <i class="far fa-star"></i>
                        @elseif ($average_rating >= 2.5 && $average_rating < 3)
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star-half-alt"></i>
                            <i class="far fa-star"></i>
                            <i class="far fa-star"></i>
                        @elseif ($average_rating >= 3 && $average_rating < 3.5)
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="far fa-star"></i>
                            <i class="far fa-star"></i>
                        @elseif ($average_rating >= 3.5 && $average_rating < 4)
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star-half-alt"></i>
                            <i class="far fa-star"></i>
                        @elseif ($average_rating >= 4 && $average_rating < 4.5)
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="far fa-star"></i>
                        @elseif ($average_rating >= 4.5 && $average_rating < 5)
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star-half-alt"></i>
                        @elseif ($average_rating >= 5)
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        @endif
                        <!-- <span style="color: #333;display: inline-block;">({{ $average_rating }})</span> -->
                    </div>
                </div>
                @if ($product->discount_price > 0)
                    <a href="{{ route('product.details', $product->slug) }}">
                        <h6><strong
                                style="color: var(--primary_color)">{{ setting('CURRENCY_ICON') ?? '৳' }}{{ $product->discount_price }}</strong>
                            <del>{{ setting('CURRENCY_ICON') ?? '৳' }}{{ $product->regular_price }}</del>
                        </h6>
                    </a>
                @else
                    <a href="{{ route('product.details', $product->slug) }}">
                        <h6><strong
                                style="color: var(--primary_color)">{{ setting('CURRENCY_ICON') ?? '৳' }}{{ $product->regular_price }}</strong>
                        </h6>
                    </a>
                @endif
                <a href="{{ route('product.details', $product->slug) }}">
                    <h5>{{ implode(' ', array_slice(explode(' ', $product->title), 0, 10)) }}...</h5>
                </a>

                @if ($product->discount_price > 0)
                    <span style="color: #ea6721;">

                        @if ($product->dis_type == '2')
                            @php($discount_price = round((($product->regular_price - $product->discount_price) / $product->regular_price) * 100) . '%')
                        @else
                            <?php
                            $currency_icon = setting('CURRENCY_ICON') ? setting('CURRENCY_ICON') : '৳';
                            $discount_price = $currency_icon . ($product->regular_price - $product->discount_price);
                            ?>
                        @endif
                        <h6 class="dis-label d-block">{{ $discount_price }} OFF</h6>
                        <h6></h6>
                    </span>
                @endif
            </div>
            <h6 class="px-2 py-1" style="line-height:.9rem;font-size:.9rem;">
                @if ($product->prdct_extra_msg)
                    <small>{{ $product->prdct_extra_msg }}</small>
                @endif
            </h6>
            <div class="quick-view"> <a href="{{ route('product.details', $product->slug) }}"><i
                        class="icofont icofont-search"></i> Quick View</a></div>
        </div>

        <div class="home-add2">

            <div class="cbtn bg-white">
                @if ($product->quantity <= '0')
                    <a href="{{ route('product.details', $product->slug) }}" class="redirect"
                        style="margin-top: 10px;background: red;color: white;border-color: red;">Pre </a>
                @else
                    @if ($product->sheba != 1)
                        <!--<button type="submit" class="redirect" style="margin-top: 10px;" data-url="{{ route('product.info', $product->slug) }}" id="productInfo" type="submit" title="Add To Cart">-->
                        <!--    <i class="fal fa-shopping-cart" aria-hidden="true"></i>-->
                        <!--</button>-->

                        <button class="cart-button--wrapper redirect" type="submit"
                            data-url="{{ route('product.info', $product->slug) }}" data-id="{{ $product->id }}"
                            type="submit" title="Add To Cart">
                            <span class="cart-button">
                                <span class="cart-button__icon">
                                    <i class="fal fa-shopping-cart" aria-hidden="true"></i>
                                </span>
                            </span>
                        </button>

                        <form action="{{ route('buy.product') }}" method="GET">
                            @if (isset($campaigns_product))
                                <input type="hidden" name="camp" id="camp"
                                    value="{{ $campaigns_product->id }}">
                                <?php $product->discount_price = $campaigns_product->price; ?>
                            @endif

                            @if (!empty($product->discount_price))
                                <input type="hidden" name="just" id="just"
                                    value="{{ $product->discount_price }}">
                                <input type="hidden" name="dynamic_price" class="dynamic_price"
                                    value="{{ $product->discount_price }}">
                            @else
                                <input type="hidden" name="just" id="just"
                                    value="{{ $product->regular_price }}">
                                <input type="hidden" name="dynamic_price" class="dynamic_price"
                                    value="{{ $product->regular_price }}">
                            @endif

                            <input type="hidden" name="id" id="id" value="{{ $product->id }}">
                            <input type="hidden" name="qty" id="qty" value="1">
                            <input type="hidden" name="color" id="color" value="blank">

                            @if (isset($attributes))
                                @foreach ($attributes as $attribute)
                                    <?php
                                    $attribute_prouct = DB::table('attribute_product')->select('*')->join('attribute_values', 'attribute_values.id', '=', 'attribute_product.attribute_value_id')->addselect('attribute_values.name as vName')->addselect('attribute_values.id as vid')->join('attributes', 'attributes.id', '=', 'attribute_values.attributes_id')->where('attribute_product.product_id', $product->id)->where('attributes.id', $attribute->id)->get();
                                    ?>
                                    @if ($attribute_prouct->count() > 0)
                                        @foreach ($attribute_prouct as $attr)
                                            <?php $vid = $attr->vid; ?>
                                        @endforeach

                                        <input type="hidden" name="{{ $attribute->slug }}"
                                            id="{{ $attribute->slug }}"
                                            value="{{ $attribute_prouct->count() == 1 ? $vid : 'blank' }}">
                                    @endif
                                @endforeach
                            @endif

                            @if ($product->quantity <= 0)
                                <p
                                    style="width:140px;margin-top: 10px;background: #ec1d1d;color: white;border-color: var(--primary_color);text-align: center;padding: 10px;border-radius: 5px;">
                                    Out Of Stock
                                </p>
                            @else
                                <!-- Replace submit button with your styled button -->
                                <button class="cart-button--wrapper redirect" type="submit" title="Add To Cart">
                                    <span class="cart-button" style="background-color: #dc0b0b;">
                                        <span class="cart-button__icon">
                                            <i class="fal fa-bolt" aria-hidden="true"></i>
                                        </span>
                                    </span>
                                </button>
                            @endif
                        </form>

                        <button class="cart-button--wrapper2" type="submit"
                            data-url="{{ route('product.info', $product->slug) }}" data-id="{{ $product->id }}">
                            <span class="cart-button">
                                <span class="cart-button__icon">
                                    <i style="color: white;" class="fal fa-exchange-alt" aria-hidden="true"></i>
                                </span>
                            </span>
                        </button>
                        {{-- 
                <button type="submit" class="redirect" style="margin-top: 10px;" data-url="{{route('product.info', $product->slug)}}" onclick="addToCart('{{$product->id}}')" type="submit" title="Add To Cart">
                    Add to cart
                </button> --}}
                    @endif
                @endif
                {{-- <form action="{{route('wishlist.add')}}" method="post" id="submit_payment_form{{$typeid}}">
                @csrf
                    <input type="hidden" name="product_id" value="{{$product->slug}}"> 
                    <button style="margin-top: 5px;background:{{$color}}" class="redirect" type="submit" title="Wishlist"><i class="fal fa-heart" aria-hidden="true"></i> </button>
                </form> --}}
            </div>
        </div>
    </div>
</div>
@push('js')
    <script>
        $(document).off('click', '.cart-button--wrapper').on('click', '.cart-button--wrapper', function(e) {
            const id = this.getAttribute('data-id');

            this.classList.add('animate');
            setTimeout(() => {
                this.classList.remove('animate');
            }, 2000);

            document.querySelector('.mobile-bottom-nav-cart-menu').classList.add('animate');
            setTimeout(() => {
                document.querySelector('.mobile-bottom-nav-cart-menu').classList.remove('animate');
            }, 500);

            fetch('/add/cart', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        _token: document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content'),
                        id,
                        qty: 1,
                        color: 'blank'
                    })
                })
                .then(response => response.json())
                .then(data => {

                    document.getElementById('total-cart-amount').textContent = Number(document.getElementById(
                        'total-cart-amount').textContent) + 1
                    document.getElementById('total-cart-amount2').textContent = Number(document.getElementById(
                        'total-cart-amount2').textContent) + 1

                    loadCartOnCanvas()
                    // $.toast({
                    //     heading: 'Congratulations',
                    //     text: data.message,
                    //     icon: 'success',
                    //     position: 'top-right',
                    //     stack: false
                    // });
                })
                .catch(error => console.error('Error updating cart:', error))
        });

        // form submit 
        $(document).on('submit', '#submit_payment_form{{ $typeid }}', function(e) {
            e.preventDefault();

            let action = $(this).attr('action');
            var formData = $(this).serialize();
            $.ajax({
                type: 'POST',
                url: action,
                data: formData,
                dataType: "JSON",
                beforeSend: function() {
                    loader(true);
                },
                success: function(response) {
                    responseMessage(response.alert, response.message, response.alert.toLowerCase())
                },
                complete: function() {
                    loader(false);
                },
                error: function(xhr) {
                    if (xhr.status == 422) {
                        if (typeof(xhr.responseJSON.errors) !== 'undefined') {

                            $.each(xhr.responseJSON.errors, function(key, error) {
                                $('small.' + key + '').text(error);
                                $('#' + key + '').addClass('is-invalid');
                            });
                            responseMessage('Error', xhr.responseJSON.message, 'error')
                        }

                    } else if (xhr.status == 401) {
                        alert('please login');
                        window.location = '/login';

                    } else {
                        responseMessage(xhr.status, xhr.statusText, 'error')
                    }
                }
            });
        });

        // response message hande
        function responseMessage(heading, message, icon) {
            $.toast({
                heading: heading,
                text: message,
                icon: icon,
                position: 'top-right',
                stack: false
            });
        }

        // loader handle this function
        function loader(status) {
            if (status == true) {
                $('#loading-image').removeClass('d-none').addClass('d-block');

            } else {
                $('#loading-image').addClass('d-none').removeClass('d-block');
            }
        }
    </script>

    <script>
        $(document).off('click', '.cart-button--wrapper2').on('click', '.cart-button--wrapper2', function(e) {
            const id = this.getAttribute('data-id');

            this.classList.add('animate');
            setTimeout(() => {
                this.classList.remove('animate');
            }, 2000);

            document.querySelector('.mobile-bottom-nav-cart-menu').classList.add('animate');
            setTimeout(() => {
                document.querySelector('.mobile-bottom-nav-cart-menu').classList.remove('animate');
            }, 500);

            fetch('/add/compare', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        _token: document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content'),
                        id
                    })
                })
                .then(response => response.json())
                .then(data => {

                    if (data.alert == 'success') document.getElementById('total-compare-amount').textContent =
                        Number(document.getElementById('total-compare-amount').textContent) + 1

                    $.toast({
                        heading: String(data.alert[0]).toUpperCase() + String(data.alert).slice(1),
                        text: data.message,
                        icon: data.alert,
                        position: 'top-right',
                        stack: false
                    });
                })
                .catch(error => console.error('Error updating cart:', error))
        });
    </script>
@endpush
