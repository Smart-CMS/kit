<?php

namespace SmartCms\Kit\Components;

use Illuminate\View\Component;
use SmartCms\Kit\Actions\Microdata\BreadcrumbsMicrodata;
use SmartCms\Kit\Models\Page;
use SmartCms\Seo\Models\Seo;

class PageComponent extends Component
{
    public function __construct(public Page $page)
    {
        app('template')->set($page->template()->orderBy('sorting')->get());
        $seo = Seo::query()->where('seoable_id', $page->id)->where('seoable_type', Page::class)->first() ?? new Seo;
        app('microdata')->add(BreadcrumbsMicrodata::run($page->getBreadcrumbs()));
        app('seo')->title($seo->title);
        app('seo')->description($seo->description);
    }

    public function render()
    {
        return view('kit::page');
    }
}
