<?php

namespace SmartCms\Kit\Admin\Resources\Pages\Tables;

use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use SmartCms\Kit\Support\Contracts\PageStatus;
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
                NameColumn::make()->getStateUsing(fn($record) => $record->getTranslation('name', main_lang())),
                ImageColumn::make('image.source')
                    ->square()
                    ->getStateUsing(fn($record) => validateImage(ltrim($record?->image['source'] ?? '', '/')))
                    ->defaultImageUrl(no_image()['source'] ?? '')
                    ->default(no_image()['source']),
                TextColumn::make('status')->badge()->color(function (mixed $state) {
                    return PageStatus::tryFrom($state)?->getColor();
                })->formatStateUsing(function (mixed $state) {
                    return PageStatus::tryFrom($state)?->getLabel();
                }),
                // SortingColumn::make(),
                ViewsColumn::make(),
                UpdatedAtColumn::make(),
                CreatedAtColumn::make(),
            ])
            ->filters([
                StatusFilter::make(),
            ])
            ->recordActions([
                // DeleteAction::make()->iconButton()->hidden(fn($record) => $record->is_system),
                // EditAction::make()->iconButton(),
                ViewRecord::make()->iconButton(),
            ])
            ->toolbarActions([]);
    }
}
