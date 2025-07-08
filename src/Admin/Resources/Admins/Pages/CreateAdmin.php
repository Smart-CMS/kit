<?php

namespace SmartCms\Kit\Admin\Resources\Admins\Pages;

use SmartCms\Kit\Admin\Resources\Admins\AdminResource;
use Filament\Resources\Pages\CreateRecord;
use SmartCms\Support\Admin\Components\Actions\SaveAction;
use SmartCms\Support\Admin\Components\Actions\SaveAndClose;

class CreateAdmin extends CreateRecord
{
    protected static string $resource = AdminResource::class;

    protected function getHeaderActions(): array
    {
        return [
            SaveAndClose::make($this, AdminResource::getUrl()),
            SaveAction::make($this),
        ];
    }
}
