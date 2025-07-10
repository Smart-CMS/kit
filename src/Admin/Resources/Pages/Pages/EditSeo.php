<?php

namespace SmartCms\Kit\Admin\Resources\Pages\Pages;

use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Filament\Schemas\Schema;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Model;
use SmartCms\Kit\Actions\Admin\GetPageListUrl;
use SmartCms\Kit\Admin\Resources\Pages\PageResource;
use SmartCms\Seo\Admin\Seos\Schemas\RelatedSeoForm;
use SmartCms\Support\Admin\Components\Actions\SaveAction;
use SmartCms\Support\Admin\Components\Actions\SaveAndClose;
use SmartCms\Support\Admin\Components\Actions\ViewRecord;

class EditSeo extends EditRecord
{
    protected static string $resource = PageResource::class;

    protected static string $relationship = 'seo';

    public static function getNavigationLabel(): string
    {
        return __('kit::admin.seo');
    }

    public static function getNavigationIcon(): string | Htmlable | null
    {
        return 'heroicon-o-globe-alt';
    }

    public function getBreadcrumb(): string
    {
        return $this->record->name;
    }

    public function form(Schema $form): Schema
    {
        return RelatedSeoForm::configure($form);
    }

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ViewRecord::make(),
            SaveAndClose::make($this, GetPageListUrl::run($this->getRecord())),
            SaveAction::make($this),
        ];
    }

    public function getIconForColumn(string $state): string
    {
        $state = strip_tags($state);
        $state = str_replace(' ', '', $state);
        if ($state && strlen($state) > 0) {
            return 'heroicon-o-check-circle';
        }

        return 'heroicon-o-x-circle';
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $this->record->seo()->updateOrCreate([], $data);

        return $record;
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        return array_merge($data, $this->record->seo?->toArray() ?? []);
    }

    public function getHeading(): string | Htmlable
    {
        return parent::getHeading() . ' ' . __('kit::admin.seo');
    }
}
