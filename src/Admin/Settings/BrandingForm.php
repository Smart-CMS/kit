<?php

namespace SmartCms\Kit\Admin\Settings;

use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Tabs\Tab;
use SmartCms\Support\Admin\Components\Forms\ImageUpload;

class BrandingForm
{
    public static function make(): Tab
    {
        return Tab::make(__('kit::admin.branding'))
            ->schema([
                Repeater::make('branding.socials')
                    ->schema([
                        TextInput::make('name')
                            ->label(__('support::admin.name'))
                            ->string()
                            ->required(),
                        TextInput::make('link')
                            ->label(__('kit::admin.url'))
                            ->string()
                            ->required(),
                        ImageUpload::make('image', 'branding', __('kit::admin.icon')),
                    ])
                    ->default([]),
            ]);
    }
}
