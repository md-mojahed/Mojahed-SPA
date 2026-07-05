<?php

namespace Mojahed\Spa\Components;

use Illuminate\View\Component;

class SpaOffcanvas extends Component
{
    public string $id;
    public string $title;
    public string $placement;
    public string $width;
    public string $loaderType;

    public function __construct(
        string $id,
        string $title = '',
        string $placement = 'end',
        string $width = '450px',
        string $loaderType = ''
    ) {
        $this->id          = $id;
        $this->title       = $title;
        $this->placement   = $placement;
        $this->width       = $width;
        $this->loaderType  = $loaderType ?: config('spa.default_loader', 'spinner');
    }

    public function render()
    {
        return view('spa::components.spa-offcanvas');
    }
}
