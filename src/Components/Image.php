<?php

namespace SmartCms\Kit\Components;

use Illuminate\View\Component;

class Image extends Component
{
    public string $alt;
    public string $src;
    public int $width;
    public int $height;

    public function __construct(?array $options = [])
    {
        if (!is_array($options)) {
            $options = [];
        }
        $this->src = validateImage($options['source']);
        $this->alt = $options[current_lang()] ?? $options['alt'] ?? $this->src;
        $this->width = $options['width'] ?? 0;
        $this->height = $options['height'] ?? 0;
    }

    public function render()
    {
        return view('kit::image');
    }
}
