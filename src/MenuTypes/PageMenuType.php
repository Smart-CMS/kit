<?php

namespace SmartCms\Kit\MenuTypes;

use Filament\Forms\Components\Field;
use Filament\Forms\Components\Select;
use SmartCms\Kit\Models\Page;
use SmartCms\Menu\MenuTypeInterface;

class PageMenuType implements MenuTypeInterface
{
    public function getType(): string
    {
        return 'page';
    }

    public function getLabel(): string
    {
        return __('kit::admin.page');
    }

    public function getSchema(): Field
    {
        return Select::make('url')
            ->options(Page::query()->where('depth', '<', 3)->pluck('name', 'id'));
    }

    public function getLinkFromItem(mixed $item): string
    {
        return Page::find($item['url'] ?? 0)?->route() ?? url('/');
    }
}
