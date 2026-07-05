<a
    href="#"
    {{ $attributes }}
    @click.prevent="spaAction({
        url:       '{{ $url }}',
        method:    '{{ $method }}',
        target:    '{{ $target }}',
        modal:     '{{ $modal }}',
        offcanvas: '{{ $offcanvas }}',
        confirm: {
            enabled: {{ $confirm ? 'true' : 'false' }},
            title:   '{{ addslashes($confirmTitle) }}',
            text:    '{{ addslashes($confirmText) }}',
            type:    '{{ $confirmType }}',
            ok:      '{{ addslashes($confirmOk) }}',
            cancel:  '{{ addslashes($confirmCancel) }}'
        },
        onSuccess: {
            reload:   '{{ $onSuccessReload }}',
            close:    '{{ $onSuccessClose }}',
            toast:    '{{ addslashes($onSuccessToast) }}',
            redirect: '{{ $onSuccessRedirect }}',
            emit:     '{{ $onSuccessEmit }}'
        }
    })"
>
    {{ $slot }}
</a>
