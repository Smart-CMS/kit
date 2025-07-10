<?php

namespace SmartCms\Kit;

use Filament\Contracts\Plugin;
use Filament\Panel;
use Filament\View\PanelsRenderHook;
use SmartCms\Forms\FormsPlugin;
use SmartCms\Kit\Actions\Admin\GetInboxButton;
use SmartCms\Kit\Actions\Admin\GetVersionHtml;
use SmartCms\Kit\Actions\Admin\GetViewButton;
use SmartCms\Kit\Admin\Pages\Dashboard;
use SmartCms\Kit\Admin\Pages\Login;
use SmartCms\Kit\Admin\Pages\Profile;
use SmartCms\Kit\Admin\Pages\Settings;
use SmartCms\Kit\Admin\Resources\Admins\AdminResource;
use SmartCms\Kit\Admin\Resources\Pages\PageResource;
use SmartCms\Kit\Admin\Widgets\HealthCheck;
use SmartCms\Kit\Http\Middlewares\NoIndex;
use SmartCms\Menu\MenuPlugin;
use SmartCms\PanelTranslate\PanelTranslatePlugin;
use SmartCms\TemplateBuilder\TemplateBuilderPlugin;
use SmartCms\Theme\Theme;

class KitPlugin implements Plugin
{
    public function getId(): string
    {
        return 'kit';
    }

    public function register(Panel $panel): void
    {
        $panel->plugins([
            new Theme,
            TemplateBuilderPlugin::make('kit::admin.design'),
            PanelTranslatePlugin::make('kit::admin.system'),
            MenuPlugin::make('kit::admin.design'),
            FormsPlugin::make(),
        ])
            ->profile(Profile::class, isSimple: false)
            ->login(Login::class)
            ->authGuard('admin')
            ->topNavigation()
            ->brandName(app('s')->get('company_name', 'SmartCms'))
            ->spa()
            ->databaseNotifications()
            ->resources([
                AdminResource::class,
                PageResource::class,
            ])
            ->widgets([
                HealthCheck::class,
            ])
            ->middleware([
                NoIndex::class,
            ])
            ->renderHook(PanelsRenderHook::PAGE_END, GetVersionHtml::run())
            ->renderHook(PanelsRenderHook::HEAD_START, fn(): string => '<meta name="robots" content="noindex, nofollow" />')
            ->renderHook(PanelsRenderHook::GLOBAL_SEARCH_AFTER, GetInboxButton::run())
            ->renderHook(PanelsRenderHook::GLOBAL_SEARCH_AFTER, GetViewButton::run())
            ->pages([
                Dashboard::class,
                Settings::class,
            ]);
    }

    public function boot(Panel $panel): void {}

    public static function make(): static
    {
        return app(static::class);
    }

    public static function get(): static
    {
        /** @var static $plugin */
        $plugin = filament(app(static::class)->getId());

        return $plugin;
    }
}
