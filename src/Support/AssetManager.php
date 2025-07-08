<?php

namespace SmartCms\Kit\Support;

class AssetManager
{
    protected array $css = [];
    protected array $js = [];

    public function __construct()
    {
        if (file_exists(resource_path('css/app.css'))) {
            $this->addCss('resources/css/app.css', 0);
        }
        if (file_exists(resource_path('js/app.js'))) {
            $this->addJs('resources/js/app.js', 0);
        }
    }

    public function addCss(string $path, int $priority = 0): void
    {
        $this->css[] = ['path' => $path, 'priority' => $priority];
    }

    public function addJs(string $path, int $priority = 0): void
    {
        $this->js[] = ['path' => $path, 'priority' => $priority];
    }

    public function getCss(): array
    {
        return collect($this->css)->sortBy('priority')->pluck('path')->unique()->toArray();
    }

    public function getJs(): array
    {
        return collect($this->js)->sortBy('priority')->pluck('path')->unique()->toArray();
    }
}
