<?php

namespace Mojahed\Spa\Components;

use Illuminate\View\Component;

class SpaLoader extends Component
{
    public string $type;
    public int $rows;
    public int $cols;
    public string $height;

    public function __construct(
        string $type = '',
        int $rows = 5,
        int $cols = 4,
        string $height = '16px'
    ) {
        $this->type   = $type ?: config('spa.default_loader', 'spinner');
        $this->rows   = $rows;
        $this->cols   = $cols;
        $this->height = $height;
    }

    public function render()
    {
        return view('spa::components.spa-loader');
    }
}
