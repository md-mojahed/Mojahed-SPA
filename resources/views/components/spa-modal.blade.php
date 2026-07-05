<div
    class="modal fade"
    id="{{ $id }}"
    tabindex="-1"
    aria-labelledby="{{ $id }}-label"
    aria-hidden="true"
    @if($staticBackdrop) data-bs-backdrop="static" data-bs-keyboard="false" @endif
    x-data="spaContainer('{{ $id }}', 'modal')"
    @spa-load.window="spaHandleLoad($event)"
    @spa-reset.window="spaHandleReset($event)"
>
    <div class="modal-dialog modal-{{ $size }} @if($scrollable) modal-dialog-scrollable @endif">
        <div class="modal-content">

            {{-- Loader shown while loading --}}
            <div x-show="loading" class="modal-body">
                <x-spa-loader :type="$loaderType" />
            </div>

            {{-- Content injected here --}}
            <div x-show="!loading" x-html="content"></div>

            {{-- Default footer with close button --}}
            <div class="modal-footer" x-show="!loading">
                {{ $slot }}
                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>

        </div>
    </div>
</div>
