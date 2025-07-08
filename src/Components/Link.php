<?php

namespace SmartCms\Kit\Components;

use Illuminate\View\Component;

class Link extends Component
{
    public string $title;

    public string $url;

    public string $target;

    public function __construct(?array $options = null)
    {
        if (! is_array($options)) {
            $options = [];
        }
        $this->title = $options['title'] ?? '';
        $this->url = $options['url'] ?? '';
        $this->target = $options['target'] ?? '_self';
    }

    public function render()
    {
        return view('kit::link');
    }
}
