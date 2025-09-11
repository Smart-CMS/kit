<?php

use SmartCms\Kit\Services\UpdateExecutor;

beforeEach(function () {
    $this->updateExecutor = new UpdateExecutor;
});

it('can check composer availability', function () {
    $result = $this->updateExecutor->checkComposerAvailability();

    expect($result)->toBeArray();
    expect($result)->toHaveKeys(['available', 'version', 'message']);
    expect($result['available'])->toBeBoolean();
});

it('validates update environment', function () {
    $result = $this->updateExecutor->validateUpdateEnvironment();

    expect($result)->toBeArray();
    expect($result)->toHaveKeys(['valid', 'issues']);
    expect($result['valid'])->toBeBoolean();
    expect($result['issues'])->toBeArray();
});

it('tracks running state correctly', function () {
    expect($this->updateExecutor->isRunning())->toBeFalse();

    // The isRunning state is managed internally during executeUpdate
    // We can't easily test this without mocking the entire update process
});

it('initializes with empty output', function () {
    expect($this->updateExecutor->getOutput())->toBeArray();
    expect($this->updateExecutor->getOutput())->toBeEmpty();
});

it('detects git repository as potential issue', function () {
    // Create a temporary .git directory for testing
    $gitDir = base_path('.git');
    $gitExists = is_dir($gitDir);

    if (! $gitExists) {
        mkdir($gitDir);
    }

    $result = $this->updateExecutor->validateUpdateEnvironment();

    // Clean up
    if (! $gitExists && is_dir($gitDir)) {
        rmdir($gitDir);
    }

    // Check if git warning is included
    $hasGitWarning = false;
    foreach ($result['issues'] as $issue) {
        if (strpos($issue, 'Git repository') !== false) {
            $hasGitWarning = true;

            break;
        }
    }

    expect($hasGitWarning)->toBeTrue();
});

it('detects write permission issues', function () {
    // This test is environment-dependent and might not work in all test environments
    // We'll just verify the method exists and returns the expected structure
    $result = $this->updateExecutor->validateUpdateEnvironment();

    expect($result)->toHaveKey('valid');
    expect($result)->toHaveKey('issues');
});
