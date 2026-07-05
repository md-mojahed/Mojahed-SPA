<div
    id="{{ $id }}"
    x-data="spaForm({
        url:    '{{ $url }}',
        method: '{{ $method }}',
        model:  '{{ $model }}',
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
</div>
