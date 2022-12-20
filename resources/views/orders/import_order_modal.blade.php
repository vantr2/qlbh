<!-- Modal -->
<div class="modal fade" id="import_order_modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('orders.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">{{ __('Import order') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <button class="btn btn-primary" type="button"
                        onclick="downloadSampleData()">{{ __('Download sample data') }}</button>
                    <div class="my-3">
                        <input type="file" class="form-control form-control-user @error('file') is-invalid @enderror"
                            id="exampleFile" name="file" value="{{ old('file') }}"
                            accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">
                        @error('file')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary"
                        data-bs-dismiss="modal">{{ __('Close') }}</button>
                    <button type="submit" class="btn btn-primary">{{ __('Import') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        function downloadSampleData() {
            window.location.href = '{{ route('orders.download_sample') }}';
        }

        $(function() {
            $('#import_order_modal').on('hidden.bs.modal', function() {
                $('input[name="file"]').val('');
            })
        })
    </script>
@endpush
