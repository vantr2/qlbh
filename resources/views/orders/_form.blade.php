<form action="{{ route('orders.store') }}" method="post">
    @csrf
    @if (isset($orderInfo))
        <input type="hidden" name="_id" value="{{ $orderInfo->id }}">
    @endif
    <input type="hidden" name="detail_data"
        value='{{ old('detail_data', isset($orderInfo) ? $orderInfo->detail_data : '[]') }}'>
    <div class="row">
        <div class="col-6">
            <div class="mb-3">
                <label for="customer_id" class="form-label">{{ __('Customer') }}</label>
                <select name="customer_id" class='form-select form-control @error('customer_id') is-invalid @enderror'>
                    <option value="" selected>{{ __('Please choose...') }}</option>
                    @foreach ($customers as $customer)
                        @php
                            $value = '';
                            if (isset($orderInfo)) {
                                if ($orderInfo->customer) {
                                    $value = old('customer_id', isset($orderInfo) ? $orderInfo->customer->id : '');
                                }
                            }
                        @endphp
                        <option value="{{ $customer->id }}" {{ $value == $customer->id ? 'selected' : '' }}>
                            {{ $customer->first_name . ' ' . $customer->last_name }}</option>
                    @endforeach
                </select>
                @error('customer_id')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </div>
        <div class="col-6">
            <div class="mb-3">
                <label for="order_date" class="form-label">{{ __('Order Date') }}</label>
                <input type="date" class="form-control @error('order_date') is-invalid @enderror" id="order_date"
                    name="order_date" value="{{ old('order_date', isset($orderInfo) ? $orderInfo : '') }}">

                @error('order_date')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </div>
    </div>

    <div class="row mt-3">
        <h5 class="font-weight-bold">{{ __('Detail') }}</h5>
        <div class="col-2">
            <input type="hidden" id="product_can_update" value="">
        </div>
        <div class="col-3">
            <div class="form-group">
                <label for="product_id" class="form-label">{{ __('Product') }}</label>
                <select name="product_id" class='form-select form-control'>
                    <option value="" selected>{{ __('Please choose...') }}</option>
                    @foreach ($products as $product)
                        <option value="{{ $product->id }}" data-price="{{ $product->price }}">
                            {{ $product->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-3">
            <label for="quantity" class="form-label">{{ __('Quantity') }}</label>
            <input type="number" class="form-control @error('quantity') is-invalid @enderror" id="quantity"
                name="quantity" min="0" max="1000000" value="{{ old('quantity', '') }}">
            @error('quantity')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <div class="col-2">
            <label for="" class="form-label opacity-0">{{ __('Q') }}</label>
            <div class="d-flex ">
                <div class="btn btn-primary add-product" onclick="addProduct()">
                    {{ __('Add') }}
                </div>
                <div class="btn btn-secondary ms-2" onclick="clearProductForm()">{{ __('Clear') }}</div>
            </div>
        </div>

        {{-- <div class="col-2 d-none align-items-end action-for-update">
            <div class="btn btn-primary save-product" onclick="saveProduct()">
                {{ __('Save') }}
            </div>
            <div class="btn btn-secondary ms-2" onclick="closeProductForm()">{{ __('Close') }}</div>
        </div> --}}

        <div class="col-2"></div>
        <div class="col-12 mt-3">
            <table id="tbl_order_details" class="table table-hover table-bordered w-100">
                <thead>
                    <tr>
                        <th>{{ __('Product') }}</th>
                        <th>{{ __('Unit Price') }}</th>
                        <th>{{ __('Quantity') }}</th>
                        <th>{{ __('Total Amount') }}</th>
                        <th>{{ __('Action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    <tr id="row_no_data" class="d-none">
                        <td colspan="5" class="text-center">{{ __('There is no data') }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="col-9 mt-3"></div>
        <div class="col-3  mt-3 total-container">
            <strong>{{ __('Total: ') }}</strong>
            <input type="text" readonly name="total" class="form-control"
                value="{{ old('total', isset($orderInfo) ? $orderInfo->total : '0') }}">
        </div>
    </div>
    <div class="d-flex mt-4">
        <a class="btn btn-secondary px-3" href="{{ route('orders.list') }}">{{ __('Back') }}</a>
        <button type="submit" class="btn btn-primary ms-2">{{ __('Submit') }}</button>
    </div>
</form>
@include('orders.row_template')

@push('scripts')
    <script>
        var productCount = 0;

        function showError(element, message) {
            $(element).addClass('is-invalid').parent().append(
                `
                        <div class="invalid-feedback">
                            ${ message }
                        </div>
                    `
            );
        }

        function clearError(element, message) {
            $(element).removeClass('is-invalid').parent().find('.invalid-feedback').remove();
        }

        function validateProductForm() {
            var productId = $('select[name="product_id"]').val();
            var quantity = $('input[name="quantity"]').val();

            clearError('select[name="product_id"]');
            clearError('input[name="quantity"]');

            var isValid = true;
            if (!productId) {
                showError('select[name="product_id"]', '{{ __('Please choose one') }}')
                isValid = false;
            }

            if (!quantity) {
                showError('input[name="quantity"]', '{{ __('Please input') }}')
                isValid = false;
            }

            return isValid;
        }

        function clearProductForm() {
            $('select[name="product_id"]').val('');
            $('input[name="quantity"]').val('');
            $('#product_can_update').val('');
        }

        function fillData(availableData = null) {
            var productId = availableData ? availableData.id : $('select[name="product_id"]').val();
            var productName = availableData ? availableData.name : $('select[name="product_id"] option:selected').text();
            var productPrice = availableData ? availableData.price : $('select[name="product_id"] option:selected').data(
                'price');
            var quantity = availableData ? availableData.quantity : $('input[name="quantity"]').val();

            var amount = parseInt(productPrice) * parseInt(quantity);

            $('.row-template tr').attr('id', productId);
            $('.row-template .detail-name').text(productName);
            $('.row-template .detail-price').text(productPrice);
            $('.row-template .detail-qty').text(quantity);
            $('.row-template .detail-amount').text(amount);
            $('.row-template .detail-action .btn-delete').attr('onclick', `deleteProduct('${productId}')`);

            if (availableData) {
                return;
            }

            var detailData = JSON.parse($('input[name="detail_data"]').val());
            detailData.push({
                id: productId,
                name: productName,
                price: productPrice,
                quantity: quantity,
                amount: amount,
            });
            $('input[name="detail_data"]').val(JSON.stringify(detailData));
        }

        function deleteHiddenData(productId) {
            var detailData = JSON.parse($('input[name="detail_data"]').val());
            detailData = detailData.filter(function(item) {
                return item.id != productId;
            })
            $('input[name="detail_data"]').val(JSON.stringify(detailData));
        }

        function mergeQuantityData(productId, newQuantity) {
            var detailData = JSON.parse($('input[name="detail_data"]').val());
            detailData = detailData.map(function(item) {
                if (item.id == productId) {
                    item.quantity = parseInt(newQuantity);
                    item.amount = parseInt(newQuantity) * parseInt(item.price);
                }
                return item;
            });
            $('input[name="detail_data"]').val(JSON.stringify(detailData));
        }

        function checkProductDuplicate() {
            var productId = $('select[name="product_id"]').val();
            return $('tr#' + productId).length > 0;
        }

        function updateAvailableProduct() {
            var productId = $('select[name="product_id"]').val();
            var productPrice = $('select[name="product_id"] option:selected').data('price');

            var quantity = $('input[name="quantity"]').val();
            var oldQuantity = $('tr#' + productId).find('.detail-qty').text();
            var newQuantity = parseInt(quantity) + parseInt(oldQuantity);

            var newAmount = parseInt(productPrice) * newQuantity;

            $('tr#' + productId).find('.detail-qty').text(newQuantity);
            $('tr#' + productId).find('.detail-amount').text(newAmount);

            mergeQuantityData(productId, newQuantity);
            clearProductForm();
            syncTotal();
        }

        function addProduct(availableData = null) {
            if (!availableData) {
                if (!validateProductForm()) {
                    return;
                }

                if (checkProductDuplicate()) {
                    // update quantity
                    updateAvailableProduct();
                    return;
                }
            }

            // create new
            fillData(availableData);
            $('#tbl_order_details tbody').append(
                $('.row-template tbody').html()
            ).find('#row_no_data').addClass('d-none');

            if (!availableData) {
                productCount++;
            }

            clearProductForm();
            syncTotal();
        }

        function deleteProduct(productId) {
            if (confirm("{{ __('Do you want to delele this item?') }}")) {
                $('#tbl_order_details tr#' + productId).remove();
                productCount--;

                console.log(productCount);
                if (productCount == 0) {
                    $('#tbl_order_details tbody #row_no_data').removeClass('d-none');
                }

                deleteHiddenData(productId);
                syncTotal();
            }
        }

        function syncTotal() {
            var detailData = JSON.parse($('input[name="detail_data"]').val());
            var total = 0;
            detailData.forEach(function(item) {
                total += item.amount;
            });

            $('input[name="total"]').val(total);
        }

        function initalData() {
            var detailData = JSON.parse($('input[name="detail_data"]').val());

            productCount = detailData.length;
            if (productCount == 0) {
                $('#tbl_order_details tbody #row_no_data').removeClass('d-none');
            } else {
                $('#tbl_order_details tbody #row_no_data').addClass('d-none');
            }

            detailData.forEach(function(item) {
                addProduct(item);
            });
            syncTotal();
        }

        $(function() {
            initalData();
        });
    </script>
@endpush
