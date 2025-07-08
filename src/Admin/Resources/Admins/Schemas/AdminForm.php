<?php

namespace SmartCms\Kit\Admin\Resources\Admins\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use SmartCms\Support\Admin\Components\Layout\FormGrid;

class AdminForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                FormGrid::make([Section::make([
                    TextInput::make('username')
                        ->label(__('kit::admin.username'))
                        ->required()->unique(ignoreRecord: true),
                    TextInput::make('email')
                        ->label(__('kit::admin.email'))
                        ->email()->unique(ignoreRecord: true)
                        ->required(),
                    TextInput::make('password')
                        ->label(__('kit::admin.password'))
                        ->password()
                        ->required()
                ])
                    // ->compact()
                    ->columnSpan(2),]),
            ])->columns(1);
    }
}
