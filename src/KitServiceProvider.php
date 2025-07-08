<?php

namespace SmartCms\Kit;

use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Routing\Route;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route as FacadesRoute;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Vite;
use Livewire\Features\SupportTesting\Testable;
use Livewire\Livewire;
use SmartCms\Forms\Models\ContactForm;
use SmartCms\Kit\Actions\Support\BindConfig;
use SmartCms\Kit\Actions\Support\RegisterVariableTypes;
use SmartCms\Kit\Commands\MakeAdmin;
use SmartCms\Kit\Commands\MakeHomePage;
use SmartCms\Kit\Commands\Update;
use SmartCms\Kit\Components\Footer;
use SmartCms\Kit\Components\Gtm;
use SmartCms\Kit\Components\Header;
use SmartCms\Kit\Components\Heading;
use SmartCms\Kit\Components\Image;
use SmartCms\Kit\Components\Link;
use SmartCms\Kit\Components\Layout;
use SmartCms\Kit\Components\PageComponent;
use SmartCms\Kit\Components\Theme;
use SmartCms\Kit\Support\Seo;
use SmartCms\Kit\Http\Middlewares\HtmlMinifier;
use SmartCms\Kit\Http\Middlewares\Maintenance;
use SmartCms\Kit\Http\Middlewares\UserIdentifierMiddleware;
use SmartCms\Kit\MenuTypes\PageMenuType;
use SmartCms\Kit\Observers\ContactFormObserver;
use SmartCms\Kit\Support\AssetManager;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Spatie\LaravelPackageTools\Package;
use SmartCms\Kit\Testing\TestsKit;
use SmartCms\Kit\Support\MicrodataManager;
use SmartCms\Lang\Middlewares\Lang;
use SmartCms\Menu\MenuRegistry;

class KitServiceProvider extends PackageServiceProvider
{
    public static string $name = 'kit';

    public static string $viewNamespace = 'kit';

    public function configurePackage(Package $package): void
    {
        $package->name(static::$name)
            ->hasCommands([
                MakeAdmin::class,
                Update::class,
                MakeHomePage::class,
                // Install::class,
            ])
            ->hasConfigFile()
            ->hasMigrations([
                'create_admins_table',
                'create_pages_table',
            ])
            ->hasTranslations()
            ->hasRoute('web')
            ->hasViews('kit')
            ->hasViewComponents('kit', Layout::class, Footer::class, Theme::class, Gtm::class, Header::class, PageComponent::class, Heading::class, Image::class, Link::class)
            ->hasInstallCommand(function (InstallCommand $command) {
                $command
                    ->publish('images')
                    ->startWith(function (InstallCommand $command) {
                        $command->callSilently("vendor:publish", [
                            '--tag' => "settings-migrations",
                        ]);
                        $command->callSilently("vendor:publish", [
                            '--tag' => "lang-migrations",
                        ]);
                        $command->callSilently("vendor:publish", [
                            '--tag' => "seo-migrations",
                        ]);
                        $command->callSilently("vendor:publish", [
                            '--tag' => "menu-migrations",
                        ]);
                        $command->callSilently("vendor:publish", [
                            '--tag' => "forms-migrations",
                        ]);
                        $command->callSilently("vendor:publish", [
                            '--tag' => "template-builder-migrations",
                        ]);
                        $command->callSilently("vendor:publish", [
                            '--tag' => "model-translate-migrations",
                        ]);
                        $command->callSilently("vendor:publish", [
                            '--tag' => "notifications-migrations",
                        ]);
                        $command->callSilently('notifications:table');
                    })
                    ->publishConfigFile()
                    ->publishMigrations()
                    ->askToRunMigrations()
                    ->askToStarRepoOnGitHub('smart-cms/kit')
                    ->endWith(function (InstallCommand $command) {
                        $command->call('vendor:publish', ['--tag' => 'kit-images']);
                        $command->call('make:home-page');
                        if (File::exists(public_path('robots.txt'))) {
                            File::move(public_path('robots.txt'), public_path('robots.txt.backup'));
                        }
                        if (File::exists(public_path('sitemap.xml'))) {
                            File::move(public_path('sitemap.xml'), public_path('sitemap.xml.backup'));
                        }
                        $command->call('filament:assets');
                    });
            })
        ;
    }

    public function packageRegistered(): void
    {
        $router = $this->app->make(Router::class);
        $router->aliasMiddleware('maintenance', Maintenance::class);
        $router->aliasMiddleware('html.minifier', HtmlMinifier::class);
        $router->aliasMiddleware('uuid', UserIdentifierMiddleware::class);
        if (! Route::hasMacro('multilingual')) {
            Route::macro('multilingual', function () {
                /** @var \Illuminate\Routing\Route $this */

                $uri = $this->uri();
                $cleanUri = ltrim($uri, '/');
                FacadesRoute::addRoute(
                    $this->methods(),
                    '{lang}/' . $cleanUri,
                    $this->getAction()
                )->where('lang', '[a-z]{2}')->name('.lang')->middleware('lang');

                return $this;;
            });
        }
    }

    public function packageBooted(): void
    {
        Testable::mixin(new TestsKit);
        $this->configureDefaults();
        $this->mergeAuthConfigFrom(__DIR__ . '/../config/auth.php');
        RegisterVariableTypes::run();
        $this->app->singleton('seo', function () {
            return new Seo();
        });
        $this->app->singleton(MicrodataManager::class, function () {
            return new MicrodataManager();
        });
        $this->app->alias(MicrodataManager::class, 'microdata');
        $this->app->singleton(AssetManager::class, function () {
            return new AssetManager();
        });
        $this->app->alias(AssetManager::class, 'assets');
        ContactForm::observe(ContactFormObserver::class);
        app(MenuRegistry::class)->register(PageMenuType::class);
        BindConfig::run();

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../resources/images/' => storage_path('app/public'),
            ], 'kit-images');
        }
    }

    protected function configureDefaults(): void
    {
        Vite::useAggressivePrefetching();
        if (app()->isProduction()) {
            URL::forceHttps();
        }
        Model::automaticallyEagerLoadRelationships();
        Date::use(CarbonImmutable::class);
        DB::prohibitDestructiveCommands(
            app()->isProduction(),
        );
        Model::shouldBeStrict();
        Model::unguard();
        Livewire::setUpdateRoute(function ($handle) {
            $isAdmin = request()->is('admin/*');
            if ($isAdmin) {
                return FacadesRoute::post('/livewire/update', $handle);
            }

            return FacadesRoute::post('/livewire/update', $handle)
                ->middleware(['web', Lang::class]);
        });
    }

    protected function mergeAuthConfigFrom(string $path)
    {
        $custom = require $path;

        foreach ($custom as $key => $values) {
            $existing = config("auth.$key", []);
            config(["auth.$key" => array_merge($existing, $values)]);
        }
    }
}
