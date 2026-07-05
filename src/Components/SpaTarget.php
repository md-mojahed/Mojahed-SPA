<?php

namespace Mojahed\Spa\Components;

use Illuminate\View\Component;

class SpaTarget extends Component
{
    public string $id;
    public string $url;
    public bool $autoLoad;
    public string $loaderType;
    public string $loaderRows;
    public string $loaderCols;
    public string $method;
    public array $params;

    public function __construct(
        string $id,
        string $url = '',
        bool $autoLoad = false,
        string $loaderType = '',
        string $loaderRows = '5',
        string $loaderCols = '4',
        string $method = 'get',
        array $params = []
    ) {
        $this->id         = $id;
        $this->url        = $url;
        $this->autoLoad   = $autoLoad;
        $this->loaderType = $loaderType ?: config('spa.default_loader', 'spinner');
        $this->loaderRows = $loaderRows;
        $this->loaderCols = $loaderCols;
        $this->method     = strtolower($method);
        $this->params     = $params;
    }

    public function render()
    {
        return view('spa::components.spa-target');
    }
}
