<button
    {{ $attributes }}
    @click='spaAction(@json($config))'
>
    {{ $slot }}
</button>
