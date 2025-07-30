<?php

namespace SmartCms\Kit\Actions\Admin;

use Closure;
use Filament\Actions\Action;
use Filament\Support\Enums\IconPosition;
use Illuminate\Support\Facades\Blade;
use SmartCms\Forms\Admin\Resources\ContactForms\ContactFormResource;
use SmartCms\Forms\Enums\ContactFormStatusesEnum;
use SmartCms\Forms\Models\ContactForm;

class GetInboxButton
{
    public static function run(): Closure
    {
        return function (): string {
            $count = ContactForm::query()->where('status', ContactFormStatusesEnum::NEW)->count();
            return Blade::render('{{$action}}', [
                'action' => Action::make('contact_form')
                    ->label(__('kit::admin.inbox'))
                    ->link()
                    ->badge($count)
                    ->badgeColor($count > 0 ? 'warning' : 'gray')
                    ->extraAttributes([
                        'class' => 'mr-2'
                    ])
                    ->icon('heroicon-o-envelope')
                    ->iconPosition(IconPosition::After)
                    ->iconSize('sm')
                    ->outlined()
                    ->size('sm')
                    ->color('gray')
                    ->url(ContactFormResource::getUrl()),
            ]);
        };
    }
}
