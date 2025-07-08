<?php

namespace SmartCms\Kit\Models\Front;

use SmartCms\Kit\Models\Page;

class FrontPage extends Page
{
    public static $staticCasts = [];

    public static function addDynamicCast($attribute, $cast): void
    {
        self::$staticCasts[$attribute] = $cast;
    }

    public function getCasts(): array
    {
        return array_merge(self::$staticCasts, $this->casts);
    }
}
