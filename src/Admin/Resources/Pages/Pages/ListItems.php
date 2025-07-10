<?php

namespace SmartCms\Kit\Admin\Resources\Pages\Pages;

use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Builder;
use SmartCms\Kit\Actions\Admin\GetPageNavigation;
use SmartCms\Kit\Admin\Forms\PageNameField;
use SmartCms\Kit\Admin\Forms\PageSlugField;
use SmartCms\Kit\Admin\Resources\Pages\PageResource;
use SmartCms\Kit\Models\Page;

class ListItems extends ListRecords
{
    protected static string $resource = PageResource::class;

    public Page $rootPage;

    public function mount(): void
    {
        $this->rootPage = Page::find(request('record'));
    }

    public function getTitle(): string | Htmlable
    {
        return $this->rootPage->name . ' ' . __('kit::admin.items');
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('_create')
                ->label(__('kit::admin.create_item'))
                ->schema([
                    PageNameField::make(),
                    PageSlugField::make(),
                    Select::make('parent_id')->hidden(! $this->rootPage->settings['is_categories'])
                        ->options(Page::query()->where('parent_id', $this->rootPage->id)->pluck('name', 'id')->toArray())->required(),
                ])->action(function (array $data) {
                    Page::query()->create([
                        'name' => $data['name'],
                        'slug' => $data['slug'],
                        'parent_id' => $this->rootPage->settings['is_categories'] ? $data['parent_id'] : $this->rootPage->id,
                        'root_id' => $this->rootPage->id,
                    ]);
                }),
        ];
    }

    public function getBreadcrumbs(): array
    {
        return [];
    }

    public function getSubNavigation(): array
    {
        return array_merge(parent::getSubNavigation(), GetPageNavigation::run());
    }

    public function table(Table $table): Table
    {
        return $table->modifyQueryUsing(function (Builder $query) {
            return $query->when($this->rootPage->settings['is_categories'], function (Builder $query) {
                return $query->where('root_id', $this->rootPage->id)->where('parent_id', '!=', $this->rootPage->id);
            }, function (Builder $query) {
                return $query->where('parent_id', $this->rootPage->id);
            });
        });
    }
}
