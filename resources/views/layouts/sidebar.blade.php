@php
    $menuItems = [
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
    ];
@endphp


<nav id="sidebar">
    <div class="p-4">
        <h3 class="text-light py-4">QLBH</h3>
        <ul class="list-unstyled components mb-5">
            @foreach ($menuItems as $item)
                <li class="{{ request()->is($item['prefix'] . '*') ? 'active' : '' }}">
                    <a href="{{ $item['route'] }}">{{ $item['name'] }}</a>
                </li>
            @endforeach
        </ul>
    </div>
</nav>
