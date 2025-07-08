<?php

namespace SmartCms\Kit\Http\Handlers;

use Illuminate\Http\Request;
use SmartCms\Kit\Models\Front\FrontPage;
use Symfony\Component\HttpKernel\Attribute\Cache;

class PageHandler
{
    public $limit = 3;

    #[Cache(public: true, maxage: 31536000, mustRevalidate: true)]
    public function __invoke(Request $request): string
    {
        $segments = $request->segments();
        foreach ($segments as $key => $value) {
            if ($value == current_lang()) {
                unset($segments[$key]);
            }
        }
        if ($this->limit < count($segments)) {
            return abort(404);
        }
        $page = $this->findPage($segments);

        return $page?->render() ?? abort(404);
    }

    protected function findPage(array $segments, $parentId = null)
    {
        $slug = array_shift($segments);
        $page = FrontPage::query()->where('slug', $slug ?? '')
            ->where('parent_id', $parentId)
            ->first();
        if (! $page) {
            return null;
        }
        if (count($segments) > 0) {
            return $this->findPage($segments, $page->id);
        }

        return $page;
    }
}
