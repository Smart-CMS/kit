<?php

namespace SmartCms\Kit\Admin\Resources\Pages\Pages;

use SmartCms\Kit\Admin\Resources\Pages\PageResource;
use Filament\Actions\DeleteAction;
use Filament\Pages\Enums\SubNavigationPosition;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Contracts\Support\Htmlable;
use SmartCms\Kit\Actions\Admin\GetPageListUrl;
use SmartCms\Support\Admin\Components\Actions\SaveAction;
use SmartCms\Support\Admin\Components\Actions\SaveAndClose;
use SmartCms\Support\Admin\Components\Actions\ViewRecord;

class EditPage extends EditRecord
{
    protected static string $resource = PageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ViewRecord::make(),
            SaveAndClose::make($this, GetPageListUrl::run($this->getRecord())),
            SaveAction::make($this),
        ];
    }

    public static function getNavigationLabel(): string
    {
        return __('kit::admin.edit');
    }
}
