@php
    $menuItems = [
        [
            'name' => __('User'),
            'route' => route('users.list'),
            'prefix' => 'users',
        ],
        [
            'name' => __('Company'),
            'route' => route('companies.list'),
            'prefix' => 'companies',
        ],
        [
            'name' => __('Customer'),
            'route' => route('customers.list'),
            'prefix' => 'customers',
        ],
        [
            'name' => __('Product'),
            'route' => route('products.list'),
            'prefix' => 'products',
        ],
        [
            'name' => __('Order'),
            'route' => route('orders.list'),
            'prefix' => 'orders',
        ],
    ];
@endphp


<nav id="sidebar">
    <div class="p-4">
        <h3 class="text-light py-4 home-title">
            <a href="{{ route('home') }}">{{ __('QLBH') }}</a>
        </h3>
        <ul class="list-unstyled components mb-5">
            @foreach ($menuItems as $item)
                <li class="{{ request()->is($item['prefix'] . '*') ? 'active' : '' }}">
                    <a href="{{ $item['route'] }}">{{ $item['name'] }}</a>
                </li>
            @endforeach
        </ul>
    </div>
</nav>
