<?php

namespace SmartCms\Kit\Components;

use Illuminate\View\Component;

class Theme extends Component
{
    public function render()
    {
        return <<<'blade'
        <style>
        :root {@foreach(app('s')->get('theme', []) as $key => $value)--{{$key}}: {{$value ?? '#000'}};@endforeach}
        </style>
        blade;
    }
}
