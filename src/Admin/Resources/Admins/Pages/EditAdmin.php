<?php

namespace SmartCms\Kit\Admin\Resources\Admins\Pages;

use Filament\Actions\Action;
use SmartCms\Kit\Admin\Resources\Admins\AdminResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Icons\Heroicon;
use SmartCms\Kit\Admin\Components\Actions\SaveAndClose;

class EditAdmin extends EditRecord
{
    protected static string $resource = AdminResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            $this->getSaveFormAction()->label(__('kit::admin.save_close'))
                ->icon(Heroicon::OutlinedCheckBadge)
                ->action(function () {
                    $this->save(true, true);
                    $this->record->touch();

                    return redirect()->to(static::$resource::getUrl('index'));
                })->formId('form'),
            $this->getSaveFormAction()
                ->label(__('kit::admin.save'))
                ->icon(Heroicon::OutlinedCheckCircle)
                ->action(function () {
                    $this->save();
                    $this->record->touch();
                })
                ->formId('form')
        ];
    }
}
