<?php

namespace Mojahed\Spa\Components;

use Illuminate\View\Component;

class SpaModal extends Component
{
    public string $id;
    public string $size;
    public string $title;
    public string $loaderType;
    public bool $staticBackdrop;
    public bool $scrollable;

    // Serialized config passed safely to JS via @json
    public array $config;

    public function __construct(
        string $id,
        string $size = 'lg',
        string $title = '',
        string $loaderType = '',
        bool $staticBackdrop = true,
        bool $scrollable = false
    ) {
        $this->id             = $id;
        $this->size           = $size;
        $this->title          = $title;
        $this->loaderType     = $loaderType ?: config('spa.default_loader', 'spinner');
        $this->staticBackdrop = $staticBackdrop;
        $this->scrollable     = $scrollable;

        $this->config = [
            'id'   => $this->id,
            'type' => 'modal',
        ];
    }

    public function render()
    {
        return view('spa::components.spa-modal');
    }
}
