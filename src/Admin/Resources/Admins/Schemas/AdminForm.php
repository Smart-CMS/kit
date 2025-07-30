<?php

namespace SmartCms\Kit\Admin\Resources\Admins\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use SmartCms\Support\Admin\Components\Layout\Aside;
use SmartCms\Support\Admin\Components\Layout\FormGrid;
use SmartCms\Support\Admin\Components\Layout\LeftGrid;
use SmartCms\Support\Admin\Components\Layout\RightGrid;

class AdminForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                FormGrid::make()->schema([
                    LeftGrid::make()->schema([
                        Section::make([
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
                                ->required(),
                        ])
                            // ->compact()
                            ->columnSpan(2),
                    ]),
                    RightGrid::make()->schema([Aside::make()]),
                ]),
            ])->columns(1);
    }
}
