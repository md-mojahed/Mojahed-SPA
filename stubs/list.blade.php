@if($items->isEmpty())
    <div class="text-center py-5 text-muted">
        <i class="fas fa-inbox fa-2x mb-2 d-block"></i>
        No items found.
    </div>
@else
    <table class="table table-sm table-hover mb-0">
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Status</th>
                <th class="text-center">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($items as $item)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $item->name }}</td>
                    <td>{{ $item->status }}</td>
                    <td class="text-center">

                        {{-- View in offcanvas --}}
                        <x-spa-link
                            offcanvas="details-offcanvas"
                            url="{{ route('items.show', $item->id) }}"
                            class="btn btn-xs btn-outline-info">
                            <i class="fas fa-eye"></i>
                        </x-spa-link>

                        {{-- Edit in modal --}}
                        <x-spa-btn
                            modal="form-modal"
                            url="{{ route('items.edit', $item->id) }}"
                            class="btn btn-xs btn-outline-primary">
                            <i class="fas fa-edit"></i>
                        </x-spa-btn>

                        {{-- Delete with confirm --}}
                        <x-spa-btn
                            url="{{ route('items.destroy', $item->id) }}"
                            method="DELETE"
                            confirm="true"
                            confirm-title="Delete this item?"
                            confirm-text="This action cannot be undone."
                            on-success-reload="#table-wrapper"
                            on-success-toast="Item deleted."
                            class="btn btn-xs btn-outline-danger">
                            <i class="fas fa-trash"></i>
                        </x-spa-btn>

                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endif
