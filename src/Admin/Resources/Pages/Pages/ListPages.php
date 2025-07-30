<?php

namespace SmartCms\Kit\Admin\Resources\Pages\Pages;

use Filament\Actions\Action;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Width;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use SmartCms\Kit\Actions\Admin\GetPageNavigation;
use SmartCms\Kit\Admin\Forms\PageNameField;
use SmartCms\Kit\Admin\Forms\PageSlugField;
use SmartCms\Kit\Admin\Resources\Pages\PageResource;
use SmartCms\Kit\Models\Page;
use SmartCms\Support\Admin\Components\Actions\TemplateAction;
use SmartCms\TemplateBuilder\Models\Section;

class ListPages extends ListRecords
{
    protected static string $resource = PageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // TemplateAction::make()
            //     ->fillForm(function (): array {
            //         return [
            //             'template' => setting('static_page_template', []),
            //         ];
            //     })
            //     ->action(function (array $data): void {
            //         $oldTemplate = setting('static_page_template', []);
            //         if ($data['template'] == $oldTemplate) {
            //             return;
            //         }
            //         setting([
            //             'static_page_template' => $data['template'],
            //         ]);
            //     })->extraModalFooterActions([
            //         Action::make('add_section')
            //             ->schema([
            //                 Select::make('section_id')->options(Section::query()->pluck('name', 'id'))->label(__('kit::admin.section'))->required(),
            //             ])->action(function (array $data): void {
            //                 Page::query()->whereNull('parent_id')->whereNull('root_id')->each(function ($page) use ($data) {
            //                     $maxSorting = $page->template()->max('sorting');
            //                     $page->template()->create([
            //                         'section_id' => $data['section_id'],
            //                         'sorting' => $maxSorting + 1,
            //                     ]);
            //                 });
            //             }),
            //         Action::make('remove_section')->schema([
            //             Select::make('section_id')->options(Section::query()->pluck('name', 'id'))->label(__('kit::admin.section'))->required(),
            //         ])->action(function (array $data): void {
            //             Page::query()->whereNull('parent_id')->whereNull('root_id')->each(function ($page) use ($data) {
            //                 $page->template()->where('section_id', $data['section_id'])->delete();
            //             });
            //         }),
            //     ])
            //     ->schema(function ($form) {
            //         return $form
            //             ->schema([
            //                 Repeater::make('template')
            //                     ->hiddenLabel()
            //                     ->schema([
            //                         Select::make('section_id')->options(Section::query()->pluck('name', 'id'))->label(__('kit::admin.section'))->required(),
            //                     ]),
            //             ]);
            //     }),
            Action::make('create_menu_section')
                ->label(__('kit::admin.create_menu_section'))
                ->color('gray')
                ->modal()
                ->modalWidth(Width::TwoExtraLarge)
                ->schema(function (Schema $form) {
                    return $form->schema([
                        PageNameField::make(),
                        PageSlugField::make(),
                        Toggle::make('is_categories')->label(__('kit::admin.is_categories'))->default(false),
                    ]);
                })->action(function ($data) {
                    if (! isset($data['slug'])) {
                        $data['slug'] = \Illuminate\Support\Str::slug($data['name'][main_lang()]);
                    }
                    $page = Page::query()->create([
                        'name' => $data['name'],
                        'slug' => $data['slug'],
                        'is_root' => true,
                        'settings' => [
                            'is_categories' => $data['is_categories'],
                        ],
                    ]);
                    Notification::make(__('kit::admin.menu_section_created'))->success();

                    return redirect(ListPages::getUrl(['record' => $page->id]));
                }),
            Action::make('_create')->label(__('kit::admin.new_page'))
                ->modalWidth(Width::ExtraLarge)
                ->modal()->color('primary')->schema([
                    PageNameField::make(),
                    PageSlugField::make(),
                ])->action(function (array $data) {
                    Page::query()->create([
                        'name' => $data['name'],
                        'slug' => $data['slug'],
                        'parent_id' => null,
                        'root_id' => null,
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
            return $query->where('is_root', false)->whereNull('parent_id');
        });
    }
    // public function getMaxContentWidth(): Width
    // {
    //     return Width::Full;
    // }
}
