<?php

namespace SmartCms\Kit\Admin\Resources\Pages\Tables;

use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Table;
use SmartCms\Support\Admin\Components\Actions\ViewRecord;
use SmartCms\Support\Admin\Components\Filters\StatusFilter;
use SmartCms\Support\Admin\Components\Tables\CreatedAtColumn;
use SmartCms\Support\Admin\Components\Tables\NameColumn;
use SmartCms\Support\Admin\Components\Tables\SortingColumn;
use SmartCms\Support\Admin\Components\Tables\StatusColumn;
use SmartCms\Support\Admin\Components\Tables\UpdatedAtColumn;
use SmartCms\Support\Admin\Components\Tables\ViewsColumn;

class PagesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                NameColumn::make(),
                ImageColumn::make('image.source')
                    ->circular()
                    ->defaultImageUrl(no_image()['source'] ?? '')

                    ->default(no_image()['source']),
                StatusColumn::make(),
                SortingColumn::make(),
                ViewsColumn::make(),
                UpdatedAtColumn::make(),
                CreatedAtColumn::make(),
            ])
            ->filters([
                StatusFilter::make(),
            ])
            ->recordActions([
                DeleteAction::make()->iconButton(),
                EditAction::make()->iconButton(),
                ViewRecord::make()->iconButton(),
            ])
            ->toolbarActions([]);
    }
}
