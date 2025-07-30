<?php

namespace SmartCms\Kit\Admin\Resources\Admins\Pages;

use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Auth;
use SmartCms\Kit\Admin\Resources\Admins\AdminResource;

class ListAdmins extends ListRecords
{
    protected static string $resource = AdminResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
            // ->visible(fn(): bool => Auth::user()->id == 1),
        ];
    }
}
