<?php

namespace SmartCms\Kit\Actions\Admin;

use Closure;
use Filament\Actions\Action;
use Filament\Support\Enums\IconPosition;
use Illuminate\Support\Facades\Blade;

class GetViewButton
{
    public static function run(): Closure
    {
        return function (): string {
            return Blade::render('{{$action}}', [
                'action' => Action::make('view')
                    ->label(__('filament-actions::view.single.label'))
                    ->icon('heroicon-o-eye')
                    ->outlined()
                    ->iconPosition(IconPosition::Before)
                    ->size('sm')
                    ->color('gray')
                    ->url(url('/'))
                    ->openUrlInNewTab()
            ]);
        };
    }
}
