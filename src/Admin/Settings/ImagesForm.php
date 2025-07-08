<?php

namespace SmartCms\Kit\Admin\Settings;

use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section as ComponentsSection;
use Filament\Schemas\Components\Tabs\Tab;
use SmartCms\Support\Admin\Components\Forms\ImageUpload;

class ImagesForm
{
    public static function make(): Tab
    {
        return Tab::make(__('kit::admin.images'))->schema([
            ImageUpload::make('no_image')
                ->label(__('kit::admin.no_image')),
            ComponentsSection::make(__('kit::admin.resize'))->schema([
                Toggle::make('resize.enabled')
                    ->label(__('kit::admin.resize_enabled'))
                    ->formatStateUsing(function ($state) {
                        return $state ?? true;
                    })->live(),
                Toggle::make('resize.two_sides')
                    ->label(__('kit::admin.resize_two_sides'))
                    ->formatStateUsing(function ($state) {
                        return $state ?? true;
                    })->hidden(function ($get) {
                        return ! $get('resize.enabled');
                    }),
                Toggle::make('resize.autoscale')
                    ->label(__('kit::admin.resize_autoscale'))
                    ->formatStateUsing(function ($state) {
                        return $state ?? false;
                    })->hidden(function ($get) {
                        return ! $get('resize.enabled');
                    }),
            ]),
        ]);
    }
}
