<?php

namespace SmartCms\Kit\Admin\Resources\Admins\Pages;

use SmartCms\Kit\Admin\Resources\Admins\AdminResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use SmartCms\Support\Admin\Components\Actions\HelpAction;

class ListAdmins extends ListRecords
{
    protected static string $resource = AdminResource::class;

    protected function getHeaderActions(): array
    {
        return [
            HelpAction::make(__('kit::admin.help_admins')),
            CreateAction::make(),
        ];
    }
}
