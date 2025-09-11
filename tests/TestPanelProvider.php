<?php

namespace SmartCms\Kit\Tests;

use Filament\Panel;
use Filament\PanelProvider;
use SmartCms\Kit\KitPlugin;

class TestPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('/admin')
            ->authGuard('admin')
            ->plugin(KitPlugin::make());
    }
}
