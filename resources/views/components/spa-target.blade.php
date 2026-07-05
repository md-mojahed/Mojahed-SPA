<div
    id="{{ $id }}"
    x-data="spaContainer('{{ $id }}', 'target')"
    @spa-load.window="spaHandleLoad($event)"
    @spa-reset.window="spaHandleReset($event)"
    @if($autoLoad && $url)
        x-init="spaLoad({
            url: '{{ $url }}',
            method: '{{ $method }}',
            params: @js($params)
        })"
    @endif
>
    {{-- Loader --}}
    <div x-show="loading">
        <x-spa-loader :type="$loaderType" :rows="(int)$loaderRows" :cols="(int)$loaderCols" />
    </div>

    {{-- Content --}}
    <div x-show="!loading" x-html="content"></div>

</div>
