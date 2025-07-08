<?php

namespace SmartCms\Kit\Components;

use Illuminate\View\Component;

class Heading extends Component
{
    public string $title;

    public string $tag;

    public function __construct(?object $options = null, ?string $tag = 'h2', ?string $title = null)
    {
        $this->title = $options->title ?? $title ?? '';
        $this->tag = $options->tag ?? $tag ?? 'h2';
    }

    public function render()
    {
        return view('kit::heading');
    }
}
