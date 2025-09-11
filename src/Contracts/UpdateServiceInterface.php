<?php

namespace SmartCms\Kit\Contracts;

interface UpdateServiceInterface
{
    /**
     * Get the current version of the package.
     */
    public function getCurrentVersion(): string;

    /**
     * Get the latest version available from GitHub.
     */
    public function getLatestVersion(): ?string;

    /**
     * Get detailed information about a specific version.
     */
    public function getVersionInfo(string $version): ?array;

    /**
     * Check if updates are available.
     */
    public function hasUpdatesAvailable(): bool;

    /**
     * Get complete update details including current and latest versions.
     */
    public function getUpdateDetails(): ?array;
}
