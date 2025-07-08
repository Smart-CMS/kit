<?php

namespace SmartCms\Kit\Admin\Resources\Pages\Pages;

use Filament\Actions\Action;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use SmartCms\Kit\Actions\Admin\GetPageNavigation;
use SmartCms\Kit\Admin\Resources\Pages\PageResource;
use SmartCms\Kit\Models\Page;
use SmartCms\Support\Admin\Components\Forms\NameField;
use SmartCms\Support\Admin\Components\Forms\SlugField;

class ListCategories extends ListRecords
{
    protected static string $resource = PageResource::class;

    public Page $rootPage;

    public function mount(): void
    {
        $this->rootPage = Page::find(request('record'));
        parent::mount();
    }

    public function getTitle(): string | Htmlable
    {
        return $this->rootPage->name . ' ' . __('kit::admin.categories');
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('_create')
                ->label(__('kit::admin.create_category'))
                ->schema([
                    NameField::make()->live(onBlur: true)->afterStateUpdated(function (string $state, string $operation, Set $set, Get $get) {
                        if ($operation == 'edit') {
                            return;
                        }
                        $slug = Str::slug($state);
                        $currentslug = $get('slug') ?? $slug;
                        if (str_contains($slug, $currentslug)) {
                            $set('slug', $slug);
                        }
                    }),
                    SlugField::make(),
                ])->action(function (array $data) {
                    Page::query()->create([
                        'name' => $data['name'],
                        'slug' => $data['slug'],
                        'parent_id' => $this->rootPage->id,
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
            return $query->where('parent_id', $this->rootPage->id);
        });
    }
}
