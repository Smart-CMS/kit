<?php

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use SmartCms\Kit\Admin\Clusters\System\Pages\UpdatePage;
use SmartCms\Kit\Contracts\UpdateCheckerInterface;
use SmartCms\Kit\Contracts\UpdateServiceInterface;
use SmartCms\Kit\Models\Admin;

beforeEach(function () {
    $this->admin = Admin::factory()->create();
    Cache::flush();
});

it('can render update page when updates are enabled', function () {
    Config::set('kit.updates.enabled', true);

    $this->actingAs($this->admin, 'admin')
        ->get(UpdatePage::getUrl())
        ->assertSuccessful();
});

it('shows navigation item when updates are enabled', function () {
    Config::set('kit.updates.enabled', true);

    expect(UpdatePage::shouldRegisterNavigation())->toBeTrue();
});

it('hides navigation item when updates are disabled', function () {
    Config::set('kit.updates.enabled', false);

    expect(UpdatePage::shouldRegisterNavigation())->toBeFalse();
});

it('displays current version', function () {
    Config::set('kit.updates.enabled', true);

    $mockUpdateService = Mockery::mock(UpdateServiceInterface::class);
    $mockUpdateService->shouldReceive('getCurrentVersion')->andReturn('1.0.0');

    $this->app->instance(UpdateServiceInterface::class, $mockUpdateService);

    $this->actingAs($this->admin, 'admin')
        ->get(UpdatePage::getUrl())
        ->assertSee('1.0.0');
});

it('shows update available section when updates exist', function () {
    Config::set('kit.updates.enabled', true);

    $updateDetails = [
        'current_version' => '1.0.0',
        'latest_version' => '1.1.0',
        'has_updates' => true,
        'release_info' => [
            'tag_name' => 'v1.1.0',
            'body' => 'New features and bug fixes',
            'published_at' => '2024-01-15T10:00:00Z'
        ]
    ];

    $mockUpdateChecker = Mockery::mock(UpdateCheckerInterface::class);
    $mockUpdateChecker->shouldReceive('getUpdateNotifications')->andReturn($updateDetails);

    $this->app->instance(UpdateCheckerInterface::class, $mockUpdateChecker);

    $this->actingAs($this->admin, 'admin')
        ->get(UpdatePage::getUrl())
        ->assertSee('Update Available')
        ->assertSee('1.1.0')
        ->assertSee('New features and bug fixes');
});

it('shows up to date section when no updates exist', function () {
    Config::set('kit.updates.enabled', true);

    $updateDetails = [
        'current_version' => '1.0.0',
        'latest_version' => '1.0.0',
        'has_updates' => false
    ];

    $mockUpdateChecker = Mockery::mock(UpdateCheckerInterface::class);
    $mockUpdateChecker->shouldReceive('getUpdateNotifications')->andReturn($updateDetails);

    $this->app->instance(UpdateCheckerInterface::class, $mockUpdateChecker);

    $this->actingAs($this->admin, 'admin')
        ->get(UpdatePage::getUrl())
        ->assertSee('Up to Date')
        ->assertSee('Your system is running the latest version');
});

it('displays configuration settings', function () {
    Config::set('kit.updates.enabled', true);
    Config::set('kit.updates.check_frequency', 'daily');

    $this->actingAs($this->admin, 'admin')
        ->get(UpdatePage::getUrl())
        ->assertSee('Update Settings')
        ->assertSee('Enabled')
        ->assertSee('daily');
});

it('can trigger manual update check', function () {
    Config::set('kit.updates.enabled', true);

    $mockUpdateChecker = Mockery::mock(UpdateCheckerInterface::class);
    $mockUpdateChecker->shouldReceive('checkOnLogin')->once();
    $mockUpdateChecker->shouldReceive('getUpdateNotifications')->andReturn(null);

    $this->app->instance(UpdateCheckerInterface::class, $mockUpdateChecker);

    $this->actingAs($this->admin, 'admin')
        ->post(UpdatePage::getUrl(), [
            'action' => 'checkForUpdates'
        ])
        ->assertSuccessful();
});
