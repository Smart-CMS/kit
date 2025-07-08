<?php

namespace SmartCms\Kit\Actions\Admin;

use Closure;
use Filament\Actions\Action;
use Illuminate\Support\Facades\Blade;
use SmartCms\Forms\Admin\Resources\ContactForms\ContactFormResource;
use SmartCms\Forms\Enums\ContactFormStatusesEnum;
use SmartCms\Forms\Models\ContactForm;

class GetInboxButton
{
    public static function run(): Closure
    {
        return function (): string {
            return Blade::render('{{$action}}', [
                'action' => Action::make('contact_form')
                    ->label(__('kit::admin.inbox'))
                    ->badge(ContactForm::query()->where('status', ContactFormStatusesEnum::NEW)->count())
                    ->badgeColor('gray')
                    ->icon('heroicon-o-envelope')
                    ->outlined()
                    ->size('sm')
                    ->color('gray')
                    ->url(ContactFormResource::getUrl())
            ]);
        };
    }
}
