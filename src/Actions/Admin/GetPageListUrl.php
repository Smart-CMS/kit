<?php

namespace SmartCms\Kit\Actions\Admin;

use Lorisleiva\Actions\Concerns\AsAction;
use SmartCms\Kit\Admin\Resources\Pages\Pages\ListCategories;
use SmartCms\Kit\Admin\Resources\Pages\Pages\ListItems;
use SmartCms\Kit\Admin\Resources\Pages\Pages\ListPages;
use SmartCms\Kit\Models\Page;

class GetPageListUrl
{
    use AsAction;
    public function handle(Page $record): string
    {
        $url = ListPages::getUrl();
        if (!$record->root_id) {
            return $url;
        }
        $root = Page::query()->find($record->root_id);
        if (!$root->settings['is_categories'] || $record->parent_id != $record->root_id) {
            return ListItems::getUrl(['record' => $root->id]);
        }
        return ListCategories::getUrl(['record' => $root->id]);
    }
}
