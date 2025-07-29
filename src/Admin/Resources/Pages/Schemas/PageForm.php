<?php

namespace SmartCms\Kit\Admin\Resources\Pages\Schemas;

use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use SmartCms\Kit\Admin\Forms\PageNameField;
use SmartCms\Kit\Admin\Forms\PageSlugField;
use SmartCms\Kit\Models\Page;
use SmartCms\Support\Admin\Components\Forms\ImageUpload;
use SmartCms\Support\Admin\Components\Layout\Aside;
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
                            ])->columnSpan(2)->columns(2),
                        ]),
                        RightGrid::make()->schema([
                            Aside::make($schema->getRecord()?->id != 1),
                        ]),
                    ]),
                ]
            )->columns(1);
    }
}
