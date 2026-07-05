<div
    id="{{ $id }}"
    {{ $attributes }}
    x-data='spaForm(@json($config))'
>
    {{ $slot }}
</div>
