<?php

namespace SmartCms\Kit\Components;

use Illuminate\View\Component;
use SmartCms\TemplateBuilder\Models\Layout;

class Footer extends Component
{
    public ?Layout $layout = null;

    public function __construct()
    {
        $this->layout = app(Layout::class)->where('id', app('s')->get('footer', null))->first();
    }

    public function render()
    {
        return view($this->layout->viewPath, $this->layout->variables);
    }

    public function shouldRender()
    {
        return $this->layout !== null;
    }
}
