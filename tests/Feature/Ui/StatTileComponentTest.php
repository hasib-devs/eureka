<?php

use Illuminate\Support\Facades\Blade;

it('renders an info tile with value, label and solid color', function () {
    $html = Blade::render('<x-ui.stat-tile variant="info" :value="45" label="Total Products" icon="fas fa-box" />');

    expect($html)->toContain('45')
        ->toContain('Total Products')
        ->toContain('bg-tile-info');
});

it('renders the danger variant color', function () {
    $html = Blade::render('<x-ui.stat-tile variant="danger" :value="2" label="Cancel" icon="fas fa-x" />');

    expect($html)->toContain('bg-tile-danger');
});

it('renders a More info footer link when href is given', function () {
    $html = Blade::render('<x-ui.stat-tile variant="success" :value="30" label="Orders" icon="fas fa-cart" href="/vendor/order" />');

    expect($html)->toContain('More info')->toContain('href="/vendor/order"');
});

it('omits the footer link when no href is given', function () {
    $html = Blade::render('<x-ui.stat-tile variant="info" :value="0" label="Amount" icon="fas fa-money" />');

    expect($html)->not->toContain('More info');
});

it('renders the icon class', function () {
    $html = Blade::render('<x-ui.stat-tile variant="info" :value="1" label="X" icon="fas fa-procedures" />');

    expect($html)->toContain('fas fa-procedures');
});
