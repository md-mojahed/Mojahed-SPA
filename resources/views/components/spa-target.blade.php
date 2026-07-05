<div
    id="{{ $config['id'] }}"
    x-data='spaContainer(@json($config))'
    @spa-load.window="spaHandleLoad($event)"
    @spa-reset.window="spaHandleReset($event)"
>
    {{-- Loader --}}
    <div x-show="loading">
        <x-spa-loader :type="$loaderType" :rows="(int)$loaderRows" :cols="(int)$loaderCols" />
    </div>

    {{-- Content --}}
    <div x-show="!loading" x-html="content"></div>

</div>
