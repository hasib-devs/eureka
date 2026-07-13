{{--
    District + thana selects shared by both minimal checkouts.
    District names must match city.js thanaList() keys so the thana
    cascade keeps working. The data-* attributes feed the shipping
    calculation in checkoutShippingCharge() below.
--}}
@php
    $checkoutDistricts = [
        'Bagerhat', 'Bandarban', 'Barguna', 'Barishal', 'Bhola', 'Bogura', 'Brahmanbaria', 'Chandpur',
        'Chapai Nawabganj', 'Chattogram', 'Chuadanga', "Cox's Bazar", 'Cumilla', 'Dhaka', 'Dinajpur',
        'Faridpur', 'Feni', 'Gaibandha', 'Gazipur', 'Gopalganj', 'Habiganj', 'Jaipurhat', 'Jamalpur',
        'Jashore', 'Jhalokhathi', 'Jhenaidah', 'Khagrachhari', 'Khulna', 'Kishoreganj', 'Kurigram',
        'Kushtia', 'Lalmonirhat', 'Luxmipur', 'Madaripur', 'Magura', 'Manikganj', 'Mauluvibazar',
        'Meharpur', 'Munshiganj', 'Mymensingh', 'Naogaon', 'Narail', 'Narayanganj', 'Narsingdi',
        'Natore', 'Netrokona', 'Nilphamari', 'Noakhali', 'Pabna', 'Panchagarh', 'Patuakhali',
        'Pirojpur', 'Rajbari', 'Rajshahi', 'Rangamati', 'Rangpur', 'Satkhira', 'Shariatpur', 'Sherpur',
        'Sirajganj', 'Sunamganj', 'Sylhet', 'Tangail', 'Thakurgaon',
    ];

    $lastOrder = auth()->check() ? ($order ?? null) : null;
    $selectedDistrict = old('district', $lastOrder->district ?? '');
    $selectedThana = old('thana', $lastOrder->thana ?? '');
@endphp

<div class="form-group col-md-12">
    <label for="distr">জেলা <sup class="text-[red]">*</sup></label>
    <select name="district" id="distr" required onchange="thanaList();"
        class="form-control @error('district') is-invalid @enderror"
        data-inside-area="{{ \App\Services\ShippingCharge::insideArea() }}"
        data-charge-inside="{{ (float) (setting('shipping_charge') ?? 0) }}"
        data-charge-outside="{{ (float) (setting('shipping_charge_out_of_range') ?? 0) }}"
        data-free-above="{{ (float) (setting('shipping_free_above') ?? 0) }}">
        <option value="" disabled @if($selectedDistrict === '') selected @endif>জেলা নির্বাচন করুন</option>
        @foreach ($checkoutDistricts as $checkoutDistrict)
            <option value="{{ $checkoutDistrict }}" @if($selectedDistrict === $checkoutDistrict) selected @endif>{{ $checkoutDistrict }}</option>
        @endforeach
    </select>
    @error('district')
        <small class="form-text text-danger">{{ $message }}</small>
    @enderror
</div>

<div class="form-group col-md-12">
    <label for="polic_sta">থানা</label>
    <select name="thana" id="polic_sta" class="form-control @error('thana') is-invalid @enderror">
        <option value="">থানা নির্বাচন করুন</option>
        @if ($selectedThana !== '')
            <option value="{{ $selectedThana }}" selected>{{ $selectedThana }}</option>
        @endif
    </select>
    @error('thana')
        <small class="form-text text-danger">{{ $message }}</small>
    @enderror
</div>

<script>
    // Mirrors App\Services\ShippingCharge: inside-district rate vs outside
    // rate per seller, free once the subtotal reaches the threshold.
    function checkoutShippingCharge(subtotal, sellerCount) {
        var district = document.getElementById('distr');
        if (!district) return 0;

        var freeAbove = parseFloat(district.dataset.freeAbove) || 0;
        if (freeAbove > 0 && subtotal >= freeAbove) return 0;
        if (!district.value) return 0;

        var inside = (district.dataset.insideArea || 'Dhaka').toLowerCase();
        var single = district.value.toLowerCase() === inside
            ? (parseFloat(district.dataset.chargeInside) || 0)
            : (parseFloat(district.dataset.chargeOutside) || 0);

        return single * (parseInt(sellerCount) || 1);
    }
</script>
