<?php

namespace SmartCms\Kit\Admin\Resources\Pages\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use SmartCms\Kit\Models\Page;
use SmartCms\ModelTranslate\Admin\TranslateAction;
use SmartCms\Support\Admin\Components\Forms\ImageUpload;
use SmartCms\Support\Admin\Components\Forms\NameField;
use SmartCms\Support\Admin\Components\Forms\SlugField;
use SmartCms\Support\Admin\Components\Layout\Aside;
use SmartCms\Support\Admin\Components\Layout\FormGrid;
use SmartCms\Support\Admin\Components\Layout\LeftGrid;
use SmartCms\Support\Admin\Components\Layout\RightGrid;
use Illuminate\Support\Str;
use SmartCms\Support\Admin\Components\Forms\StatusField;

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
                                NameField::make()->live(onBlur: true)
                                    ->suffixAction(TranslateAction::make())->afterStateUpdated(function (string $state, string $operation, Set $set, Get $get) {
                                        if ($operation == 'edit') {
                                            return;
                                        }
                                        $slug = Str::slug($state);
                                        $currentslug = $get('slug') ?? $slug;
                                        if (str_contains($slug, $currentslug)) {
                                            $set('slug', $slug);
                                        }
                                    }),
                                SlugField::make()->hidden(fn($record) => $record?->id == 1),
                                // DateTimePicker::make('published_at')->seconds(false)->weekStartsOnMonday()->closeOnDateSelection()
                            ]),
                            Section::make()->schema([
                                ImageUpload::make('image', $imagePath, __('kit::admin.image')),
                                ImageUpload::make('banner', $imagePath, __('kit::admin.banner')),
                            ])->columnSpan(2)->columns(2)
                        ]),
                        RightGrid::make()->schema([
                            Aside::make(true),
                        ]),
                    ])
                ]
            )->columns(1);
    }
}
