<?php

namespace Mojahed\Spa\Components;

use Illuminate\View\Component;

class SpaBtn extends Component
{
    public string $url;
    public string $method;

    // Load targets
    public string $target;
    public string $modal;
    public string $offcanvas;

    // Confirm dialog
    public bool $confirm;
    public string $confirmTitle;
    public string $confirmText;
    public string $confirmType;
    public string $confirmOk;
    public string $confirmCancel;

    // On success actions
    public string $onSuccessReload;
    public string $onSuccessClose;
    public string $onSuccessToast;
    public string $onSuccessRedirect;
    public string $onSuccessEmit;

    // Serialized config passed safely to JS via @json
    public array $config;

    public function __construct(
        string $url = '',
        string $method = 'get',

        string $target = '',
        string $modal = '',
        string $offcanvas = '',

        bool $confirm = false,
        string $confirmTitle = '',
        string $confirmText = '',
        string $confirmType = 'warning',
        string $confirmOk = '',
        string $confirmCancel = '',

        string $onSuccessReload = '',
        string $onSuccessClose = '',
        string $onSuccessToast = '',
        string $onSuccessRedirect = '',
        string $onSuccessEmit = ''
    ) {
        $this->url       = $url;
        $this->method    = strtolower($method);

        $this->target    = $target;
        $this->modal     = $modal;
        $this->offcanvas = $offcanvas;

        $this->confirm       = $confirm;
        $this->confirmTitle  = $confirmTitle  ?: config('spa.confirm.title',  'Are you sure?');
        $this->confirmText   = $confirmText   ?: config('spa.confirm.text',   "You won't be able to revert this!");
        $this->confirmType   = $confirmType   ?: config('spa.confirm.type',   'warning');
        $this->confirmOk     = $confirmOk     ?: config('spa.confirm.ok',     'Yes, proceed!');
        $this->confirmCancel = $confirmCancel ?: config('spa.confirm.cancel', 'Cancel');

        $this->onSuccessReload   = $onSuccessReload;
        $this->onSuccessClose    = $onSuccessClose;
        $this->onSuccessToast    = $onSuccessToast;
        $this->onSuccessRedirect = $onSuccessRedirect;
        $this->onSuccessEmit     = $onSuccessEmit;

        $this->config = [
            'url'       => $this->url,
            'method'    => $this->method,
            'target'    => $this->target,
            'modal'     => $this->modal,
            'offcanvas' => $this->offcanvas,
            'confirm'   => [
                'enabled' => $this->confirm,
                'title'   => $this->confirmTitle,
                'text'    => $this->confirmText,
                'type'    => $this->confirmType,
                'ok'      => $this->confirmOk,
                'cancel'  => $this->confirmCancel,
            ],
            'onSuccess' => [
                'reload'   => $this->onSuccessReload,
                'close'    => $this->onSuccessClose,
                'toast'    => $this->onSuccessToast,
                'redirect' => $this->onSuccessRedirect,
                'emit'     => $this->onSuccessEmit,
            ],
        ];
    }

    public function render()
    {
        return view('spa::components.spa-btn');
    }
}
