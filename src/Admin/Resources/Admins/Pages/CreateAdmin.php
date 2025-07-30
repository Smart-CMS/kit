<?php

namespace SmartCms\Kit\Admin\Resources\Admins\Pages;

use Filament\Resources\Pages\CreateRecord;
use SmartCms\Kit\Admin\Resources\Admins\AdminResource;

class CreateAdmin extends CreateRecord
{
    protected static string $resource = AdminResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
