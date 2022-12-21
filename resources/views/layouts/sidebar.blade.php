@php
    $menuItems = [
        [
            'name' => __('User'),
            'route' => route('users.list'),
            'prefix' => 'users',
            'admin' => true,
            'icon' => 'fa-solid fa-gear',
        ],
        [
            'name' => __('Company'),
            'route' => route('companies.list'),
            'prefix' => 'companies',
            'admin' => true,
            'icon' => 'fa-regular fa-building',
        ],
        [
            'name' => __('Customer'),
            'route' => route('customers.list'),
            'prefix' => 'customers',
            'admin' => false,
            'icon' => 'fa-sharp fa-solid fa-users',
        ],
        [
            'name' => __('Product'),
            'route' => route('products.list'),
            'prefix' => 'products',
            'admin' => false,
            'icon' => 'fa-solid fa-box-open',
        ],
        [
            'name' => __('Order'),
            'route' => route('orders.list'),
            'prefix' => 'orders',
            'admin' => false,
            'icon' => 'fa-solid fa-receipt',
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
                @if ($item['admin'])
                    @admin
                        <li class="d-flex align-items-center {{ request()->is($item['prefix'] . '*') ? 'active' : '' }}">
                            <div class="">
                                <i class="{{ $item['icon'] }}"></i>
                            </div>
                            <a href="{{ $item['route'] }}">{{ $item['name'] }}</a>
                        </li>
                    @endadmin
                @else
                    <li class="d-flex align-items-center {{ request()->is($item['prefix'] . '*') ? 'active' : '' }}">
                        <div class="">
                            <i class="{{ $item['icon'] }}"></i>
                        </div>
                        <a href="{{ $item['route'] }}">{{ $item['name'] }}</a>
                    </li>
                @endif
            @endforeach
        </ul>
    </div>
</nav>
