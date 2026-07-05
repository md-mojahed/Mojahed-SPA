<?php

namespace Mojahed\Spa\Components;

use Illuminate\View\Component;

class SpaForm extends Component
{
    public string $id;
    public string $url;
    public string $method;
    public string $model;
    public array $data;

    public string $onSuccessReload;
    public string $onSuccessClose;
    public string $onSuccessToast;
    public string $onSuccessRedirect;
    public string $onSuccessEmit;

    public bool $confirm;
    public string $confirmTitle;
    public string $confirmText;
    public string $confirmType;
    public string $confirmOk;
    public string $confirmCancel;

    // Serialized config passed safely to JS via @json
    public array $config;

    public function __construct(
        string $url,
        string $method = 'post',
        string $id = '',
        string $model = 'form',
        array $data = [],

        string $onSuccessReload = '',
        string $onSuccessClose = '',
        string $onSuccessToast = '',
        string $onSuccessRedirect = '',
        string $onSuccessEmit = '',

        bool $confirm = false,
        string $confirmTitle = '',
        string $confirmText = '',
        string $confirmType = 'question',
        string $confirmOk = '',
        string $confirmCancel = ''
    ) {
        $this->url    = $url;
        $this->method = strtolower($method);
        $this->id     = $id ?: 'spa-form-' . uniqid();
        $this->model  = $model;
        $this->data   = $data;

        $this->onSuccessReload   = $onSuccessReload;
        $this->onSuccessClose    = $onSuccessClose;
        $this->onSuccessToast    = $onSuccessToast;
        $this->onSuccessRedirect = $onSuccessRedirect;
        $this->onSuccessEmit     = $onSuccessEmit;

        $this->confirm       = $confirm;
        $this->confirmTitle  = $confirmTitle  ?: config('spa.confirm.title',  'Are you sure?');
        $this->confirmText   = $confirmText   ?: config('spa.confirm.text',   '');
        $this->confirmType   = $confirmType;
        $this->confirmOk     = $confirmOk     ?: config('spa.confirm.ok',     'Yes, proceed!');
        $this->confirmCancel = $confirmCancel ?: config('spa.confirm.cancel', 'Cancel');

        $this->config = [
            'url'       => $this->url,
            'method'    => $this->method,
            'model'     => $this->model,
            'data'      => $this->data,
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
        return view('spa::components.spa-form');
    }
}
