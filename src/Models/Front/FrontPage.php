<?php

namespace SmartCms\Kit\Models\Front;

use Illuminate\Database\Eloquent\Casts\Attribute;
use SmartCms\Kit\Models\Page;
use SmartCms\Seo\Models\Seo;

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

    public function heading(): Attribute
    {
        return new Attribute(
            get: fn () => $this->getSeo()->heading ?? $this?->name ?? '',
        );
    }

    public function summary(): Attribute
    {
        return new Attribute(
            get: fn () => $this->getSeo()->summary ?? $this?->name ?? '',
        );
    }

    public function content(): Attribute
    {
        return new Attribute(
            get: fn () => str($this->getSeo()->content ?? '')->toHtmlString(),
        );
    }

    public function getSeo(): Seo
    {
        $seo = $this->seo ?? Seo::query()->where('seoable_id', $this->id)->where('seoable_type', Page::class)->first() ?? new Seo;

        return $seo;
    }

    public function categories(): Attribute
    {
        return new Attribute(
            get: function () {
                if ($this->parent_id) {
                    return FrontPage::query()->where('id', 0);
                }
                $settings = $this->settings ?? [];
                $isCategories = $settings['is_categories'] ?? false;
                if (! $isCategories) {
                    return FrontPage::query()->where('id', 0);
                }

                return FrontPage::query()->where('root_id', $this->id)->where('parent_id', '!=', $this->id);
            }
        );
    }

    public function items(): Attribute
    {
        return new Attribute(
            get: function () {
                if ($this->parent_id) {
                    return FrontPage::query()->where('id', 0);
                }
                $settings = $this->settings ?? [];
                $isCategories = $settings['is_categories'] ?? false;
                if (! $isCategories) {
                    return FrontPage::query()->where('root_id', $this->id)->where('parent_id', $this->id);
                }

                return FrontPage::query()->where('root_id', $this->id)->where('parent_id', '!=', $this->id);
            }
        );
    }

    public function breadcrumbs(): Attribute
    {
        return new Attribute(
            get: fn () => $this->getBreadcrumbs(),
        );
    }

    public function url(): Attribute
    {
        return new Attribute(
            get: fn () => $this->route(),
        );
    }
}
