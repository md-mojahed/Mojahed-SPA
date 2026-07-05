<div
    class="offcanvas offcanvas-{{ $placement }}"
    tabindex="-1"
    id="{{ $id }}"
    style="width: {{ $width }};"
    x-data='spaContainer(@json($config))'
    @spa-load.window="spaHandleLoad($event)"
    @spa-reset.window="spaHandleReset($event)"
>
    <div class="offcanvas-header">
        <h6 class="offcanvas-title fw-semibold" id="{{ $id }}-label">
            {{ $title }}
        </h6>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>

    <div class="offcanvas-body p-0">

        {{-- Loader --}}
        <div x-show="loading" class="p-3">
            <x-spa-loader :type="$loaderType" />
        </div>

        {{-- Content --}}
        <div x-show="!loading" x-html="content"></div>

    </div>
</div>
