<?php

namespace SmartCms\Kit\Admin\Settings;

use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Flex;
use Filament\Schemas\Components\Tabs\Tab;
use SmartCms\Support\Admin\Components\Forms\ImageUpload;

class SeoForm
{
    public static function make(): Tab
    {
        return Tab::make(__('kit::admin.seo'))->schema([
            Flex::make([
                TextInput::make('gtm')
                    ->label(__('kit::admin.google_tag'))
                    ->string(),
                Toggle::make('indexation')
                    ->inline(false)
                    ->label(__('kit::admin.indexation'))
                    ->required(),
            ]),
            Fieldset::make(__('kit::admin.title'))
                ->schema([
                    TextInput::make('title.prefix')
                        ->label(__('kit::admin.prefix'))
                        ->string()
                        ->helperText(__('kit::admin.title_prefix')),
                    TextInput::make('title.suffix')
                        ->label(__('kit::admin.suffix'))
                        ->helperText(__('kit::admin.title_suffix'))
                        ->string(),
                ]),
            Fieldset::make(__('kit::admin.description'))
                ->schema([
                    TextInput::make('description.prefix')
                        ->label(__('kit::admin.prefix'))
                        ->string()
                        ->helperText(__('kit::admin.description_prefix')),
                    TextInput::make('description.suffix')
                        ->label(__('kit::admin.suffix'))
                        ->helperText(__('kit::admin.description_suffix'))
                        ->string(),
                ]),
            Repeater::make('custom_meta')
                ->label(__('kit::admin.custom_meta'))
                ->schema([
                    TextInput::make('name')
                        ->label(__('kit::admin.name'))
                        ->string(),
                    TextInput::make('description')
                        ->label(__('kit::admin.description'))
                        ->string(),
                    Textarea::make('meta_tags')
                        ->label(__('kit::admin.meta_tags')),
                ])
                ->default([]),
            Repeater::make('custom_scripts')
                ->label(__('kit::admin.custom_scripts'))
                ->schema([
                    TextInput::make('name')
                        ->label(__('kit::admin.name'))
                        ->string(),
                    TextInput::make('description')
                        ->label(__('kit::admin.description'))
                        ->string(),
                    Textarea::make('scripts')
                        ->label(__('kit::admin.scripts')),
                ])
                ->default([]),
            TextInput::make('og_type')
                ->label(__('kit::admin.og_type')),
            ImageUpload::make('og_image', 'og_image', __('kit::admin.og_image')),
        ]);
    }
}
