{{--
    This blade is loaded as a fragment inside spa-modal.
    x-data="spaForm(...)" is provided by <x-spa-form>.
    Access errors via: errors.field_name
    Access submitting state via: submitting
--}}

<x-spa-form
    url="{{ isset($item) ? route('items.update', $item->id) : route('items.store') }}"
    method="{{ isset($item) ? 'PUT' : 'POST' }}"
    model="form"
    on-success-reload="#table-wrapper"
    on-success-close="#form-modal"
    on-success-toast="{{ isset($item) ? 'Item updated.' : 'Item created.' }}"
    x-data="{
        form: {
            name:   '{{ $item->name ?? '' }}',
            status: '{{ $item->status ?? 'active' }}',
        },
        ...spaForm({
            url:    '{{ isset($item) ? route('items.update', $item->id) : route('items.store') }}',
            method: '{{ isset($item) ? 'PUT' : 'POST' }}',
            model:  'form',
            onSuccess: {
                reload:   'table-wrapper',
                close:    'form-modal',
                toast:    '{{ isset($item) ? 'Item updated.' : 'Item created.' }}'
            }
        })
    }"
>
    <div class="modal-header">
        <h6 class="modal-title fw-semibold">
            {{ isset($item) ? 'Edit Item' : 'New Item' }}
        </h6>
    </div>

    <div class="modal-body">
        <div class="row g-3">

            <div class="col-12">
                <label class="form-label">Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control form-control-sm" x-model="form.name"
                    :class="{ 'is-invalid': errors.name }">
                <div class="invalid-feedback" x-text="errors.name"></div>
            </div>

            <div class="col-12">
                <label class="form-label">Status</label>
                <select class="form-select form-select-sm" x-model="form.status">
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>

        </div>
    </div>

    <div class="modal-footer">
        <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-sm btn-success" @click="submit()" :disabled="submitting">
            <span x-show="submitting" class="spinner-border spinner-border-sm me-1"></span>
            <span x-text="submitting ? 'Saving...' : 'Save'"></span>
        </button>
    </div>

</x-spa-form>
