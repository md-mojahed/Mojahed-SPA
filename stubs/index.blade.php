@extends('layouts.app')
@section('title', 'Items')
@section('content')

<div class="container-fluid" x-data="itemPage()">

    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h6 class="mb-0 fw-semibold">Items</h6>
            <small class="text-muted">Manage all items</small>
        </div>

        {{-- Open create form in modal --}}
        <x-spa-btn
            modal="form-modal"
            url="{{ route('items.create') }}"
            class="btn btn-sm btn-success">
            <i class="fas fa-plus me-1"></i> New Item
        </x-spa-btn>
    </div>

    {{-- List Fragment — auto-loads on page ready --}}
    <x-spa-target
        id="table-wrapper"
        url="{{ route('items.index') }}"
        auto-load="true"
        loader-type="table"
        loader-rows="6"
        loader-cols="5"
    />

    {{-- Create / Edit Modal --}}
    <x-spa-modal id="form-modal" size="lg" />

    {{-- Detail Offcanvas --}}
    <x-spa-offcanvas id="details-offcanvas" title="Item Details" width="500px" />

</div>

@endsection
@push('scripts')
<script>
    function itemPage() {
        return {
            ...spa(),
            // Add your custom Alpine properties and methods here
        };
    }
</script>
@endpush
