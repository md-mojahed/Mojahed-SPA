<a
    href="#"
    {{ $attributes }}
    @click.prevent='spaAction(@json($config))'
>
    {{ $slot }}
</a>
