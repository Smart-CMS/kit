<?php

namespace SmartCms\Kit\Admin\Resources\Pages\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Text;
use Filament\Schemas\Schema;
use Illuminate\Support\HtmlString;
use SmartCms\Kit\Admin\Forms\PageNameField;
use SmartCms\Kit\Admin\Forms\PageSlugField;
use SmartCms\Kit\Models\Admin;
use SmartCms\Kit\Models\Page;
use SmartCms\Support\Admin\Components\Forms\ImageUpload;
use SmartCms\Support\Admin\Components\Forms\StatusField;
use SmartCms\Support\Admin\Components\Layout\FormGrid;
use SmartCms\Support\Admin\Components\Layout\LeftGrid;
use SmartCms\Support\Admin\Components\Layout\RightGrid;

class PageForm
{
    public static function configure(Schema $schema): Schema
    {
        $imagePath = '';
        /**
         * @var Page $record
         */
        $record = $schema->getRecord();
        if ($record?->slug) {
            $imagePath = 'pages/' . $record->slug;
        }

        return $schema
            ->components(
                [
                    FormGrid::make()->schema([
                        LeftGrid::make()->schema([
                            Section::make([
                                PageNameField::make(),
                                PageSlugField::make()->hidden(fn ($record) => $record?->id == 1),
                            ]),
                            Section::make()->schema([
                                ImageUpload::make('image', $imagePath, __('kit::admin.image')),
                                ImageUpload::make('banner', $imagePath, __('kit::admin.banner')),
                            ])->columns(2),
                        ]),
                        RightGrid::make()->schema([
                            Section::make([
                                Text::make(function ($record) {
                                    $admin = Admin::query()->find($record->created_by);
                                    $created_by = $admin?->name ?? __('kit::admin.system');

                                    return new HtmlString('Created ' . $record->created_at->format('d.m.Y H:i') . ' - ' . $created_by);
                                })->color('neutral'),
                                Text::make(function ($record) {
                                    $admin = Admin::query()->find($record->updated_by);
                                    $updated_by = $admin?->name ?? __('kit::admin.system');

                                    return new HtmlString('Updated ' . $record->updated_at->format('d.m.Y H:i') . ' - ' . $updated_by);
                                })->color('neutral'),
                            ])->columnSpan(1)->hiddenOn('create')->compact()->secondary(),
                            Section::make()->schema([
                                DatePicker::make('published_at')->seconds(false)->default(now()),
                                StatusField::make()->hidden(fn ($record) => $record->is_system),
                                Toggle::make('is_index')->label(__('kit::admin.is_index'))->default(true),
                            ]),
                        ]),
                    ]),
                ]
            )->columns(1);
    }
}
